<?php

namespace App\Services\CBF;

class CosineSimilarityService
{
  public function calculate($vectorA, $vectorB)
  {
    $magA = $this->magnitude($vectorA);
    $magB = $this->magnitude($vectorB);

    if ($magA === 0.0 || $magB === 0.0) {
      return 0.0;
    }

    $dotProduct = $this->dotProduct($vectorA, $vectorB);

    return $dotProduct / ($magA * $magB);
  }

  public function calculateAll($tfidf)
  {
    $scores = [];

    $query = $tfidf['judul_ta'];
    $docs = $tfidf;
    unset($docs['judul_ta']);

    foreach ($docs as $dosenId => $vector) {
      $scores[$dosenId] = $this->calculate($query, $vector);
    }

    arsort($scores);

    return $scores;
  }

  protected function dotProduct($vectorA, $vectorB)
  {
    $result = 0.0;

    foreach ($vectorA as $term => $valueA) {
      if (isset($vectorB[$term])) {
        $result += $valueA * $vectorB[$term];
      }
    }

    return $result;
  }

  protected function magnitude($vector)
  {
    $sumOfSquares = 0.0;

    foreach ($vector as $term => $value) {
      $sumOfSquares += $value * $value;
    }

    return sqrt($sumOfSquares);
  }
}
