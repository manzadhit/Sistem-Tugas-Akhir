<?php

namespace App\Services\MAUT;

use App\Models\BobotKriteria;


class MAUTService
{

  public function __construct(
    protected CriteriaDataService $criteriaDataService
  ) {}

  public function buildDecisionMatrix($similarityScores)
  {
    $dosenIds = array_keys($similarityScores);

    $criteriaData = $this->criteriaDataService->getData($dosenIds);

    $matrix = [];

    foreach ($dosenIds as $dosenId) {
      $matrix[$dosenId] = [
        'similarity' => $similarityScores[$dosenId] ?? 0,
        'jabatan_fungsional' => $criteriaData[$dosenId]['jabatan_fungsional'] ?? 0,
        'jumlah_publikasi' => $criteriaData[$dosenId]['jumlah_publikasi'] ?? 0,
        'beban_bimbingan' => $criteriaData[$dosenId]['beban_bimbingan'] ?? 0,
      ];
    }

    return $matrix;
  }

  public function normalizeDecisionMatrix($decisionMatrix) {
    $criteria = $this->getActiveCriteria();

    $normalizedMatrix = [];

    foreach($criteria as $criterion) {
      $key = $criterion['key'];

      $values = array_column($decisionMatrix, $key);
      $min = min($values);
      $max = max($values);

      foreach($decisionMatrix as $dosenId => $row) {
        $value = $row[$key] ?? 0;

        if($max == $min) {
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

    foreach($criteria as $criterion) {
      $result += $criterion['weight'] * $normalizedValues[$criterion['key']];
    }

    return $result;
  }

  public function calculateAll($normalizedMatrix)
  {
    $criteria = $this->getActiveCriteria();

    $result = [];

    foreach($normalizedMatrix as $dosenId => $normalizedValues) {
      $result[$dosenId] = $this->calculate($normalizedValues, $criteria);
    }

    return $result;
  }

  public function rank($similarityScores)
  {
    $decisionMatrix = $this->buildDecisionMatrix($similarityScores);
    $normalizedMatrix = $this->normalizeDecisionMatrix($decisionMatrix);
    $scores = $this->calculateAll($normalizedMatrix);

    arsort($scores);

    return $scores;
  }

  public function rankWithDetails($similarityScores)
  {
    $decisionMatrix = $this->buildDecisionMatrix($similarityScores);
    $normalizedMatrix = $this->normalizeDecisionMatrix($decisionMatrix);
    $criteria = $this->getActiveCriteria();
    $scores = $this->calculateAll($normalizedMatrix);

    arsort($scores);

    // Build per-dosen detail: normalized value * weight for each criterion
    $details = [];
    foreach ($scores as $dosenId => $totalScore) {
      $criteriaDetails = [];
      foreach ($criteria as $criterion) {
        $key = $criterion['key'];
        $normalizedValue = $normalizedMatrix[$dosenId][$key] ?? 0;
        $weight = $criterion['weight'];
        $criteriaDetails[] = [
          'key'   => $key,
          'label' => $criterion['label'] ?? $key,
          'type'  => $criterion['type'],
          'nilai' => round($normalizedValue, 2),
          'bobot' => $weight,
          'utility' => round($normalizedValue * $weight, 2),
        ];
      }

      $details[$dosenId] = [
        'criteria' => $criteriaDetails,
        'total_score' => round($totalScore, 2),
      ];
    }

    return [
      'scores' => $scores,
      'details' => $details,
    ];
  }

  protected function getActiveCriteria()
  {
    return BobotKriteria::query()
      ->where('is_active', true)
      ->get(['key', 'label', 'weight', 'type'])
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
