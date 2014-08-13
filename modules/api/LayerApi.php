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
        
        return json_encode($this->getLayerList(intval($arrInput[0])));
	}

	/**
	 * Returns the layer structure for the map.
     * 
	 * @param int $id 
	 */
	protected function getLayerList($intId) 
	{
        // Find the requested map
        $objMap = \C4gMapsModel::findById($intId);
        
        // Only return map entries
        if ($objMap == null || !$objMap->is_map)
        {
            \HttpResultHelper::NotFound();
        }
        
        // TODO: Make this a recursive functions to get deeper branches of the tree
        
        // Get all layers on the map
        $objLayers = \C4gMapsModel::findByPid($intId);
        
        if ($objLayers != null) 
        {
            return $this->parseLayers($objLayers);
        }
	}
    
    /**
     * Summary of parseLayers
     * 
     * @param C4gMapsModel $objLayers 
     * @return array
     */
    protected function parseLayers($objLayers)
    {
        $arrResult = array();
        
        while($objLayers->next())
        {
            $arrResult[] = $this->parseLayer($objLayers);
        }
        
        return $arrResult;
    }
    
    /**
     * Summary of parseLayer
     * 
     * @param mixed $objLayer 
     * @return array
     */
    protected function parseLayer($objLayer)
    {
        return array('title' => $objLayer->id . '::' . $objLayer->name);
    }
}
