<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* div_for_slider_effect.twig */
class __TwigTemplate_bfc5b7f9593c19389386b506384428c217488f538b5a4a4df4954584718639ab extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        if ((0 === twig_compare(($context["initial_sliders_state"] ?? null), "disabled"))) {
            // line 2
            echo "    <div";
            if (array_key_exists("id", $context)) {
                echo " id=\"";
                echo twig_escape_filter($this->env, ($context["id"] ?? null), "html", null, true);
                echo "\"";
            }
            echo ">
";
        } else {
            // line 4
            echo "    ";
            // line 12
            echo "    <div";
            if (array_key_exists("id", $context)) {
                echo " id=\"";
                echo twig_escape_filter($this->env, ($context["id"] ?? null), "html", null, true);
                echo "\"";
            }
            // line 13
            echo " ";
            if ((0 === twig_compare(($context["initial_sliders_state"] ?? null), "closed"))) {
                // line 14
                echo "style=\"display: none; overflow:auto;\"";
            }
            echo " class=\"pma_auto_slider\"";
            // line 15
            if (array_key_exists("message", $context)) {
                echo " title=\"";
                echo twig_escape_filter($this->env, ($context["message"] ?? null), "html", null, true);
                echo "\"";
            }
            echo ">
";
        }
    }

    public function getTemplateName()
    {
        return "div_for_slider_effect.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  65 => 15,  61 => 14,  58 => 13,  51 => 12,  49 => 4,  39 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "div_for_slider_effect.twig", "C:\\Users\\FamiliaNatelloMedina\\Documents\\UNLu\\BasedeDatosMasivas\\SoftWare\\phpMyAdmin-5.0.3-all-languages\\templates\\div_for_slider_effect.twig");
    }
}
