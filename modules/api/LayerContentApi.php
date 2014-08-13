<?php

namespace c4g;

/**
 * Provides access to map layer contents stored in contao.
 */
class LayerContentApi
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
        
        return json_encode($this->getLayerData(intval($arrInput[0])));
	}
    
    /**
     * Returns the layer data.
     * 
     * @param int $id 
     */
	protected function getLayerData($intId) 
	{
        // Find the requested layer
        $objLayer = \C4gMapsModel::findById($intId);
        
        // TODO: Apply additional filter logic
        // Hidden layers or layers that only represent maps should not return.
        
        // Only return map entries
        if ($objLayer == null)
        {
            \HttpResultHelper::NotFound();
        }
        
        // TODO: Add additional data modes
        
        // Remark:
        // All Layer information should be returned as geo-JSON.
        // Even single location entries.
        
        switch ($objLayer->type)
        {
            case "geoJSON":
                return array(
                    "id" => $intId,
                    "title" => "asdf",
                    "type" => "geoJSON",
                    "data" => ""
                );
                break;
            default:
                \HttpResultHelper::InternalServerError();
        }
	}
    
    protected function createGeoJsonResult($objLayer)
    {
        return array(
            "id" => $intId,
            "title" => "asdf",
            "type" => "geoJSON",
            "data" => ""
        );
    }
}
