<div class="contentBody">
if (!isset($this->elements['sec_id']->attributes['value'])) {
    $this->elements['sec_id']->attributes['value'] = '';
    $this->elements['sec_id']->attributes['value'] .=  htmlspecialchars($t->sec_id);
}
$_attributes_used = array('value');
echo $this->elements['sec_id']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['sec_id']->attributes[$_a]);
}}
?>