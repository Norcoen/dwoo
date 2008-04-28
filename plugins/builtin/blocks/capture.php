<?php

/**
 * TOCOM
 *
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 *
 * This file is released under the LGPL
 * "GNU Lesser General Public License"
 * More information can be found here:
 * {@link http://www.gnu.org/copyleft/lesser.html}
 *
 * @author     Jordi Boggiano <j.boggiano@seld.be>
 * @copyright  Copyright (c) 2008, Jordi Boggiano
 * @license    http://www.gnu.org/copyleft/lesser.html  GNU Lesser General Public License
 * @link       http://dwoo.org/
 * @version    0.3.4
 * @date       2008-04-09
 * @package    Dwoo
 */
class DwooPlugin_capture extends DwooBlockPlugin implements DwooICompilableBlock
{
	public function init($name = 'default', $assign = null, $cat = false)
	{
	}

	public static function preProcessing(DwooCompiler $compiler, array $params, $prepend='', $append='', $type)
	{
		return DwooCompiler::PHP_OPEN.$prepend.'ob_start();'.$append.DwooCompiler::PHP_CLOSE;
	}

	public static function postProcessing(DwooCompiler $compiler, array $params, $prepend='', $append='')
	{
		$params = $compiler->getCompiledParams($params);

		$out = DwooCompiler::PHP_OPEN.$prepend."\n".'$tmp = ob_get_clean();';
		if($params['cat'] === 'true') {
			$out .= "\n".'$tmp = $this->readVar(\'dwoo.capture.\'.'.$params['name'].') . $tmp;';
		}
		if($params['assign'] !== "null") {
			$out .= "\n".'$this->scope['.$params['assign'].'] = $tmp;';
		}
		return $out . "\n".'$this->globals[\'capture\']['.$params['name'].'] = $tmp;'.$append.DwooCompiler::PHP_CLOSE;
	}
}

?>