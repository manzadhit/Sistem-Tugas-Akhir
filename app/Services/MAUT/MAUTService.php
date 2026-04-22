<?php

namespace App\Services\MAUT;

use App\Models\BobotKriteria;


class MAUTService
{

  public function __construct(
    protected CriteriaDataService $criteriaDataService
  ) {}

  public function buildDecisionMatrix($similarityScores, $context, $mahasiswa)
  {
    if($context == 'pembimbing') {
      return $this->buildDecisionMatrixPembimbing($similarityScores, $mahasiswa);
    }

    if($context == 'penguji') {
      return $this->buildDecisionMatrixPenguji($similarityScores);
    }
  }

  public function buildDecisionMatrixPembimbing($similarityScores,  $mahasiswa)
  {
    $dosenIds = array_keys($similarityScores);
    $matrix = $this->criteriaDataService->getPembimbingRekomendationData($dosenIds, $mahasiswa);

    foreach ($dosenIds as $id) {
      $matrix[$id]['similarity'] = $similarityScores[$id];
    }

    return $matrix;
  }

  public function buildDecisionMatrixPenguji($similarityScores)
  {
    $dosenIds = array_keys($similarityScores);
    $matrix = $this->criteriaDataService->getPengujiRekomendationData($dosenIds);

    foreach ($dosenIds as $id) {
      $matrix[$id]['similarity'] = $similarityScores[$id];
    }

    return $matrix;
  }

  public function normalizeDecisionMatrix($decisionMatrix, $criteria)
  {
    if (empty($decisionMatrix)) {
      return [];
    }

    $normalizedMatrix = [];

    foreach ($criteria as $criterion) {
      $key = $criterion['key'];

      $values = array_column($decisionMatrix, $key);
      $min = min($values);
      $max = max($values);

      foreach ($decisionMatrix as $dosenId => $row) {
        $value = $row[$key] ?? 0;

        if ($max == $min) {
          $normalizedMatrix[$dosenId][$key] = 1;
          continue;
        }

        $normalizedMatrix[$dosenId][$key] = $criterion['type'] == 'cost'
          ? $this->normalizeCost($value, $min, $max)
          : $this->normalizeBenefit($value, $min, $max);
      }
    }

    return $normalizedMatrix;
  }

  public function calculate($normalizedValues, $criteria)
  {
    $result = 0.0;

    foreach ($criteria as $criterion) {
      $result += $criterion['weight'] * $normalizedValues[$criterion['key']];
    }

    return $result;
  }

  public function calculateAll($normalizedMatrix, $criteria)
  {
    $result = [];

    foreach ($normalizedMatrix as $dosenId => $normalizedValues) {
      $result[$dosenId] = $this->calculate($normalizedValues, $criteria);
    }

    return $result;
  }

  public function rankWithDetails($similarityScores, $context, $mahasiswa = null)
  {
    if (empty($similarityScores)) {
      return [];
    }

    $criteria = $this->getActiveCriteria($context);

    $decisionMatrix = $this->buildDecisionMatrix($similarityScores, $context, $mahasiswa);
    $normalizedMatrix = $this->normalizeDecisionMatrix($decisionMatrix, $criteria);
    $scores = $this->calculateAll($normalizedMatrix, $criteria);

    arsort($scores);

    $details = [];

    foreach ($scores as $dosenId => $totalScore) {
      $criteriaDetails = [];

      foreach ($criteria as $criterion) {
        $key = $criterion['key'];
        $weight = $criterion['weight'];
        $normalizedValue = $normalizedMatrix[$dosenId][$key] ?? 0;
        $utilityScore = $normalizedValue * $weight;

        $criteriaDetails[] = [
          'key'   => $key,
          'weight' => $weight,
          'label' => $criterion['label'],
          'type'  => $criterion['type'],
          'normalized_value' => round($normalizedValue, 2),
          'utility_score' => round($utilityScore, 2),
        ];
      }

      usort($criteriaDetails, fn($a, $b) => $b['weight'] <=> $a['weight']);

      $details[$dosenId] = [
        'criteria_details' => $criteriaDetails,
        'total_score' => round($totalScore, 2),
      ];
    }

    return $details;
  }

  protected function getActiveCriteria($context)
  {
    return BobotKriteria::query()
      ->where('context', $context)
      ->where('is_active', true)
      ->select(['key', 'label', 'weight', 'type'])
      ->get()
      ->toArray();
  }

  protected function normalizeBenefit($value, $min, $max)
  {
    $result =  ($value - $min) / ($max - $min);

    return $result;
  }

  protected function normalizeCost($value, $min, $max)
  {
    $result =  ($max - $value) / ($max - $min);

    return $result;
  }
}
