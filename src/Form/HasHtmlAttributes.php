<?php


namespace Encore\ModalForm\Form;


trait HasHtmlAttributes
{
    /**
     * @var string[]
     */
    protected $attributes;

    /**
     * Adding value to html attribute
     * @param string $key
     * @param string $value
     * @return $this
     */
    protected function addAttribute(string $key, string $value){
        if (!isset($this->attributes[$key])){
            $this->setAttribute($key, $value);
        }
        $this->attributes[$key] = $this->mergeValues($this->attributes[$key], $value);
        return $this;
    }

    /**
     * @param string $val1
     * @param string $val2
     * @return string
     */
    private function mergeValues(string $val1, string $val2){
        $values = array_merge(
            empty($val1)?[]:explode(' ',$val1),
            empty($val2)?[]:explode(' ',$val2)
        );
        return implode(' ', $values);
    }

    /**
     * Returns value of html attribute
     * @param $key
     * @return string|null
     */
    protected function getAttribute($key){
        return isset($this->attributes[$key])?$this->attributes[$key]:null;
    }

    /**
     * Setting value of html attribute. If value already exists, it's replaced.
     * @param string $key
     * @param string $value
     * @return $this
     */
    protected function setAttribute(string $key, string $value){
        $this->attributes[$key] = '';
        $this->addAttribute($key, $value);
        return $this;
    }

    /**
     *
     * @return string
     */
    protected function formatAttributes(){
        $attributes = [];
        foreach ($this->attributes as $key => $value){
            $attributes[] = "$key='$value'";
        }
        return implode(' ', $attributes);
    }

    /**
     * Initialize attributes
     */
    protected function initHtmlAttributes(){
        foreach (self::DEFAULT_ATTRIBUTES as $ATTRIBUTE => $VALUE){
            $this->setAttribute($ATTRIBUTE, $VALUE);
        }
    }
}
