<?php

namespace App\Services\CBF;

class TfIdfService
{
  public function buildVocabulary($preprocessingData)
  {
    $allTokens = [];

    $allTokens = array_merge($allTokens, $preprocessingData['judul_ta']);

    foreach ($preprocessingData['publikasi_dosen'] as $dosenId => $tokens) {
      $allTokens = array_merge($allTokens, $tokens);
    }

    $uniqueTokens = array_unique($allTokens);
    sort($uniqueTokens);

    return array_values($uniqueTokens);
  }

  public function calculateTF($docTokens, $vocabulary)
  {
    $totalTerms = count($docTokens);
    $termCount = array_count_values($docTokens);

    $tf = [];

    if ($totalTerms === 0) {
      foreach ($vocabulary as $term) {
        $tf[$term] = 0;
      }

      return $tf;
    }

    foreach ($vocabulary as $term) {
      $tf[$term] = isset($termCount[$term]) ? $termCount[$term] / $totalTerms : 0;
    }

    return $tf;
  }

  public function calculateIDF($vocabulary, $allDocuments)
  {
    $N = count($allDocuments);

    $idf = [];

    foreach ($vocabulary as $term) {
      $df = 0;

      foreach ($allDocuments as $key => $doc) {
        if (in_array($term, $doc)) {
          $df++;
        }
      }

      $idf[$term] = log10((($N + 1) / ($df + 1)) + 1);
    }

    return $idf;
  }

  public function calculateTFIDF($preprocessingData, $vocabulary)
  {
    $allDocuments = $this->getAllDocument($preprocessingData);

    $idf = $this->calculateIDF($vocabulary, $allDocuments);

    $result = [];

    foreach ($allDocuments as $docKey => $tokens) {
      $tf = $this->calculateTF($tokens, $vocabulary);

      foreach($vocabulary as $term) {
        $result[$docKey][$term] = $tf[$term] * $idf[$term];
      }
    }

    return $result;
  }

  protected function getAllDocument($preprocessingData)
  {
    $allDocuments = [];

    $allDocuments['judul_ta'] = $preprocessingData['judul_ta'];

    foreach ($preprocessingData['publikasi_dosen'] as $dosenId => $tokens) {
      $allDocuments[$dosenId] = $tokens;
    }

    return $allDocuments;
  }
}
