<?php

namespace App\Services\CBF;

class ContentBasedFilteringService
{
  public function __construct(
    protected TextPreprocessingService $textPreprocessing,
    protected TfIdfService $tfIdf,
    protected CosineSimilarityService $cosineSimilarity
  ) {}

  public function getRecommendation($referenceId, $context = 'pembimbing')
  {
    $preprocessingData = $this->textPreprocessing->preprocessing($referenceId, $context);
    $vocabulary = $this->tfIdf->buildVocabulary($preprocessingData);
    $tfidf = $this->tfIdf->calculateTFIDF($preprocessingData, $vocabulary);
    $scores = $this->cosineSimilarity->calculateAll($tfidf);

    return $scores;
  }

  public function getTopN($referenceId, $n, $context = 'pembimbing')
  {
    $recomend = $this->getRecommendation($referenceId, $context);

    return array_slice($recomend, 0, $n, true);
  }
}
