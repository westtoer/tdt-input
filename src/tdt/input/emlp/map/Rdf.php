<?php

namespace tdt\input\emlp\map;

use tdt\streamingrdfmapper\vertere\Vertere;
use tdt\streamingrdfmapper\StreamingRDFMapper;

class Rdf extends AMapper {

    private $mapping_processor;
    private $map_count;

    function __construct($model){

        parent::__construct($model);

        // Keep track of the number of chunks mapped
        $this->map_count = 1;

        $mapfile = $this->mapper->mapfile;

        // Retrieve the base uri that will serve as a base for the subjects of the triples
        $base_uri = $this->mapper->base_uri;

        $this->log("Retrieving the mapping on location $mapfile.");
        $mapping_file = file_get_contents($mapfile);

        // Provide backwards compatibility with previous datatank mapping files
        $mapping_file = str_replace('tdt:package:resource', $base_uri, $mapping_file);

        // TODO make the type a variable in the model
        $mapping_type = "Vertere";

        if(!$mapping_file){
            $this->log("The mapping file could not be retrieved on location $this->mapper->mapfile.");
        }

        $this->mapping_processor = new StreamingRDFMapper($mapping_file, $mapping_type);
        $this->mapping_processor->setBaseUri($base_uri);
    }


    /**
     * Execute the mapping of a chunk of data
     */
    public function execute(&$chunk) {

        $this->log("Executing mapping rules for data chunk $this->map_count.");

        // Retrieve an instance of an EasyRDFGraph
        $rdf_graph = $this->mapping_processor->map($chunk, true);
        $this->map_count++;

        // TODO how to know if the mapping was succesful?
        // Throws exception when mapping
        return $rdf_graph;
    }
}
