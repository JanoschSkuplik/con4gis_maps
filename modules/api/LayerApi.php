<?php

namespace c4g;

/**
 * Provides access to map layers stored in contao.
 */
class LayerApi extends \Frontend
{
	/**
     * Determines the request method and selects the appropriate data result.
     * 
     * @param  array $arrInput Fragments from request uri
     * @return mixed           JSON data
     */
  protected $arrLayers = array();
  protected $arrConfig = array();
     
	public function generate(array $arrInput)
	{
        // Only allow GET requests
        if (strtoupper($_SERVER['REQUEST_METHOD']) != 'GET')
        {
            \HttpResultHelper::MethodNotAllowed();
        }
        
        // A map id is required
        if (count($arrInput) < 1 && !is_numeric($arrInput[0]))
        {
            \HttpResultHelper::BadRequest();
        }
        
        $intParentId = intval($arrInput[0]);
        
        $this->getLayerList($intParentId);
        $this->arrConfig['countAll'] = sizeof($this->arrLayers);
        echo json_encode(array('config'=>$this->arrConfig,'layer'=>$this->arrLayers));
	}

	/**
	 * Returns the layer structure for the map.
     * 
	 * @param int $id 
	 */
	protected function getLayerList($intId, $blnIsSubLayer = false) 
	{
  	
  	    if (!$blnIsSubLayer)
  	    {
          // Find the requested map
          $objMap = \C4gMapsModel::findById($intId);
          
          // Only return map entries
          if ($objMap == null || !$objMap->is_map)
          {
              \HttpResultHelper::NotFound();
          }
        }
        
        // Get all layers on the map
        
        $objLayers = \C4gMapsModel::findPublishedByPid($intId);

        if ($objLayers != null) 
        {
            while($objLayers->next())
            {
              
              $arrLayerData = $this->parseLayer($objLayers);
              if ($blnIsSubLayer)
              {
                if (!is_array($this->arrLayers[$arrLayerData['pid']]['childs']))
                {
                  $this->arrLayers[$arrLayerData['pid']]['childs'] = array();
                }
                $this->arrLayers[$arrLayerData['pid']]['childs'][$objLayers->id] = $arrLayerData;
              }
              else
              {
                $this->arrLayers[$objLayers->id] = $arrLayerData;       
              }       
              if ($childLayerList = $this->getLayerList($objLayers->id, true))
              {
                $this->arrLayers[$objLayers->id]['hasChilds'] = true;//$arrLayerData;   
                $this->arrLayers[$objLayers->id]['childsCount'] = $childLayerList->count();
              }
              

              
            }
            return $objLayers;
        }
        return false;
	}
    
    /**
     * Summary of parseLayer
     * 
     * @param mixed $objLayer 
     * @return array
     */
    protected function parseLayer($objLayer)
    {
        $arrLayerData = array();
        $arrLayerData['id'] = $objLayer->id;
        $arrLayerData['pid'] = $objLayer->pid;
        $arrLayerData['name'] = $objLayer->name;
        //$arrLayerData = $objLayer->row();
        return $arrLayerData; //array('title' => $objLayer->id . '::' . $objLayer->name);
    }

    
}
