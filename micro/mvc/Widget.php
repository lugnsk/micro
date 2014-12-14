<?php

namespace Micro\mvc;

use Micro\base\Exception;

class Widget {
    public function widget($name, $options = [], $capture = false)
    {
        if (!class_exists($name)) {
            throw new Exception('Widget ' . $name . ' not found.');
        }

        /** @var \Micro\base\Widget $widget widget */
        $widget = new $name($options);
        $widget->init();

        if ($capture) {
            ob_start();
            $widget->run();
            $result = ob_get_clean();
        } else {
            $result = $widget->run();
        }

        $result->asWidget = true;

        unset($widget);
        echo $result;
    }
    public function beginWidget($name, $options=[])
    {
        if (!class_exists($name)) {
            throw new Exception('Widget ' . $name . ' not found.');
        }

        if (isset($GLOBALS['widgetStack'][$name])) {
            throw new Exception('This widget (' . $name . ') already started!');
        }

        /** @var \Micro\base\Widget $GLOBALS ['widgetStack'][$name] widget */
        $GLOBALS['widgetStack'][$name] = new $name($options);
        return $GLOBALS['widgetStack'][$name]->init();
    }
    public function endWidget($name)
    {
        if (!class_exists($name) OR !isset($GLOBALS['widgetStack'][$name])) {
            throw new Exception('Widget ' . $name . ' not started.');
        }

        /** @var \Micro\base\Widget $widget widget */
        $widget = $GLOBALS['widgetStack'][$name];
        unset($GLOBALS['widgetStack'][$name]);

        $v = $widget->run();
        unset($widget);

        $v->asWidget = true;

        echo $v;
    }
}