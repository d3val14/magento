<?php
class Ccc_Repricer_Model_Observer
{
  public function updateMatchingData()
  {
    $productModel = Mage::getModel('catalog/product');
    $productCollection = $productModel->getCollection()->addAttributeToSelect('entity_id');
    $productEntityIds = [];
    foreach ($productCollection as $_prod) {
      $productEntityIds[] = $_prod->getEntityId();
    }

    $competitorModel = Mage::getModel('repricer/competitor')->getCollection()->addFieldToSelect('competitor_id');
    $competitorIds = [];
    foreach ($competitorModel as $_comp) {
      $competitorIds[] = $_comp->getCompetitorId();
    }

    $matchingModel = Mage::getModel('repricer/matching');

    $existingMatches = [];
    $matchingCollection = $matchingModel->getCollection()->addFieldToSelect(['product_id', 'competitor_id']);
    foreach ($matchingCollection as $_match) {
      $existingMatches[$_match->getProductId() . '_' . $_match->getCompetitorId()] = true;
    }

    $newMatches = [];
    foreach ($productEntityIds as $_prodId) {
      foreach ($competitorIds as $_compId) {
        $key = $_prodId . '_' . $_compId;
        if (!isset($existingMatches[$key])) {
          $newMatches[] = ['product_id' => $_prodId, 'competitor_id' => $_compId];
        }
      }
    }

    if (!empty($newMatches)) {
      foreach ($newMatches as $matchData) {
        $matchingModel->setData($matchData)->save();
      }
      echo "Successfully updated matching data.";
    } else {
      echo "No new matches to update.";
    }
  }

  public function downloadCsv()
  {
      $folderPath = Mage::getBaseDir('var') . DS . 'report' . DS . 'cmonitor' . DS . 'download';
      // Get the current date in d-m-Y format
      $currentDate = date('d-m-Y');
      $files = glob($folderPath . DIRECTORY_SEPARATOR . '*_pending.csv');
      $totalUpdates = 0;
      $totalSaves = 0;
  
      foreach ($files as $file) {
          $fileModificationTime = filemtime($file);
          $timeDifference = strtotime($currentDate) - $fileModificationTime;
          if ($timeDifference <= 86400) {
              $parsedData = $this->_parseCsv($file);
              foreach ($parsedData as $row) {
                  $productId = $row['product_id'];
                  $competitorUrl = $row['Competitor Url'];
                  $competitorSku = $row['Competitor Sku'];
  
                  $matchingModel = Mage::getModel('repricer/matching')
                      ->getCollection()
                      ->addFieldToFilter('product_id', $productId)
                      ->addFieldToFilter('competitor_url', $competitorUrl)
                      ->addFieldToFilter('competitor_sku', $competitorSku)
                      ->getFirstItem();
  
                  if ($matchingModel->getId()) {
                      $matchingModel->setCompetitorPrice($row['Competitor Price']);
                      $matchingModel->setReason($row['Reason']);
                      $matchingModel->save();

                      $totalUpdates++;
                      $totalSaves++;
                  } else {
                      $totalUpdates++;
                  }
              }
  
              $newFilename = str_replace('_pending.csv', '_completed_' . $currentDate . '.csv', $file);
              rename($file, $newFilename);
          }
      }
      $totalError = $totalUpdates - $totalSaves;

      echo "Total erros  processed: $totalError\n";

      echo "Total updates done: $totalUpdates\n";

      echo "Total saves done: $totalSaves\n";
  }
  




  protected function _parseCsv($csvFile)
  {
    $parsedData = [];
    $header = [];

    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        if (!$header) {
          $header = array_map('trim', $data);
          continue;
        }
        $parsedData[] = array_combine($header, $data);
      }
      fclose($handle);
    }
    return $parsedData;
  }
  public function uploadcsv()
  {
    // Specify columns to export
    $selectedColumns = array(
      'product_id' => 'main_table.product_id',
      'product_sku' => 'sku.sku',
      'competitor_name' => 'rc.name',
      'competitor_url' => 'main_table.competitor_url',
      'competitor_sku' => 'main_table.competitor_sku'
    );

    $columns = array_keys($selectedColumns);

    // Fetch grid data
    $collection = Mage::getModel('repricer/matching')->getCollection();
    $collection->getSelect()
      ->join(
        array('rc' => Mage::getSingleton('core/resource')->getTableName('repricer/competitor')),
        'rc.competitor_id = main_table.competitor_id',
        []
      )
      ->join(
        array('sku' => 'catalog_product_entity'),
        'sku.entity_id = main_table.product_id',
        []
      )
      ->reset(Zend_Db_Select::COLUMNS)
      ->columns($selectedColumns);

    // Group data by competitor
    $competitorData = [];
    foreach ($collection as $item) {
      $competitor = $item->getData('competitor_name');
      if (!isset($competitorData[$competitor])) {
        $competitorData[$competitor] = [];
      }
      // Extract selected columns data
      $rowData = [];
      foreach ($columns as $column) {
        $rowData[] = $item->getData($column);
      }
      $competitorData[$competitor][] = $rowData;
    }

    // Export data to separate CSV files for each competitor
    foreach ($competitorData as $competitor => $data) {
      // Prepare CSV content
      $content = implode(',', $columns) . "\n";
      foreach ($data as $row) {
        $content .= implode(',', $row) . "\n";
      }

      // Generate file name with competitor name and current date in d-m-Y format
      $todayDate = date('d-m-Y');
      $fileName = $competitor . '_upload_' . $todayDate . '.csv';

      // Specify file path
      $filePath = Mage::getBaseDir('var') . DS . 'report' . DS . 'cmonitor' . DS . 'upload' . DS . $fileName;

      // Save CSV file
      file_put_contents($filePath, $content);

      // Update filename column in the ccc_repricer_competitor table
      $competitorModel = Mage::getModel('repricer/competitor')->load($competitor, 'name');
      if ($competitorModel->getId()) {
        $competitorModel->setFilename($fileName)->save();
      }

      Mage::log("Data for competitor '$competitor' exported successfully to: " . $filePath);
    }
  }

}

