<?php

namespace App\Services\CBF;

class ContentBasedFilteringService
{
  public function __construct(
    protected TextPreprocessingService $textPreprocessing,
    protected TfIdfService $tfIdf,
    protected CosineSimilarityService $cosineSimilarity
  ) {}

  public function getRecommendation(int $permintaanPembimbingId)
  {
    $preprocessingData = $this->textPreprocessing->preprocessing($permintaanPembimbingId);
    $vocabulary = $this->tfIdf->buildVocabulary($preprocessingData);
    $tfidf = $this->tfIdf->calculateTFIDF($preprocessingData, $vocabulary);
    $scores = $this->cosineSimilarity->calculateAll($tfidf);

    return $scores;
  }

  public function getTopN($permintaanPembimbingId, $n)
  {
    $recomend = $this->getRecommendation($permintaanPembimbingId);

    return array_slice($recomend, 0, $n, true);
  }
}
