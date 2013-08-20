<?php
namespace Hybridauth\Entity;

class Entity
{
    /* The entity's unqiue ID on the connected provider */
    protected $identifier = null;

    /* The adapter used to access the resource */
    protected $adapter = null;


    function __construct($adapter = null) {
        $this->setAdapter($adapter);
    }

    function setAdapter($adapter) {
        $this->adapter = $adapter;
    }

    function setIdentifier( $identifier )
    {
        $this->identifier = $identifier;
    }

    function getAdapter() {
        return $this->adapter;
    }

    function getIdentifier()
    {
        return $this->identifier;
    }

    /**
    * Backward compatiliblity with Hybridauth 2.x
    */
    public function __get( $name )
    {
        if( property_exists( get_class($this), $name) ){
            trigger_error( 'Accessing ' . get_class($this) . ' members directly will be deprecated in Hybridauth 3.1.0', E_USER_NOTICE );

            return $this->$name;
        }

        trigger_error( 'Undefined property: ' . get_class($this) . '::' . $name .' in ' . __FILE__ . ' on line ' . __LINE__, E_USER_NOTICE );
    }

    public static function parser($property,$response)
    {
        return property_exists( $response, $property ) ? $response->$property : null;
    }

    //Override this function to digest a JSON object into an entity
    public static function generateFromResponse($response,$adapter) {
        return new static($adapter);
    }

    public static function generatePostDataFromEntity($entity) {
        return array();
    }

    public function generatePostData() {
        $class = get_class($this);
        return $class::generatePostDataFromEntity($this);
    }
}
