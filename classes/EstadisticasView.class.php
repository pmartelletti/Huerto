<?phprequire_once 'AbstractView.class.php';require_once 'HTML/Template/Flexy/Element.php';require_once 'classes/Estadisticas.class.php';class EstadisticasView extends AbstractView {	var $partes;	/**	 * Constructor	 * @return unknown_type	 */	public function EstadisticasView(){		$this->partes = array();	}	public function setDisplayOptions($options){		$this->template = "estadisticas.html";				// relleno los graficos disponibles		$this->elements['gf_nombre'] = new HTML_Template_Flexy_Element;		$this->elements['gf_nombre']->setOptions($options);	}}?>