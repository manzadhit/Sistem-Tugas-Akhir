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
    $dosenIds = array_keys($similarityScores);

    $criteriaData = $this->criteriaDataService->getData($dosenIds, $context, $mahasiswa);

    $matrix = [];

    foreach ($dosenIds as $dosenId) {
      $row = [
        'similarity' => $similarityScores[$dosenId] ?? 0,
        'jabatan_fungsional' => $criteriaData[$dosenId]['jabatan_fungsional'] ?? 0,
        'jumlah_publikasi' => $criteriaData[$dosenId]['jumlah_publikasi'] ?? 0,
        'sinta_score_3y' => $criteriaData[$dosenId]['sinta_score_3y'] ?? 0,
      ];

      if ($context === 'penguji') {
        $row['beban_pengujian'] = $criteriaData[$dosenId]['beban_pengujian'] ?? 0;
      } else {
        $row['beban_bimbingan'] = $criteriaData[$dosenId]['beban_bimbingan'] ?? 0;
        $row['pemerataan_ipk'] = $criteriaData[$dosenId]['pemerataan_ipk'] ?? 0;
      }

      $matrix[$dosenId] = $row;
    }

    return $matrix;
  }

  public function normalizeDecisionMatrix($decisionMatrix, $context = 'pembimbing', $criteria = null)
  {
    if (empty($decisionMatrix)) {
      return [];
    }

    $criteria = $criteria ?? $this->getActiveCriteria($context);

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

  public function calculateAll($normalizedMatrix, $context = 'pembimbing', $criteria = null)
  {
    $criteria = $criteria ?? $this->getActiveCriteria($context);

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

    $decisionMatrix = $this->buildDecisionMatrix($similarityScores, $context, $mahasiswa);
    $criteria = $this->getActiveCriteria($context);
    $normalizedMatrix = $this->normalizeDecisionMatrix($decisionMatrix, $context, $criteria);
    $scores = $this->calculateAll($normalizedMatrix, $context, $criteria);

    arsort($scores);

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

      usort($criteriaDetails, fn($a, $b) => $b['bobot'] <=> $a['bobot']);

      $details[$dosenId] = [
        'criteria' => $criteriaDetails,
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
