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

/* display/export/options_output_radio.twig */
class __TwigTemplate_19dc2e4c68f0e58fb9827c583f342b3e5fceace7f4d835fa96a3d48825668e10 extends Template
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
        echo "<li>
    <input type=\"radio\" id=\"radio_view_as_text\" name=\"output_format\" value=\"astext\"";
        // line 3
        echo (((($context["has_repopulate"] ?? null) || (0 === twig_compare(($context["export_asfile"] ?? null), false)))) ? (" checked") : (""));
        echo ">
    <label for=\"radio_view_as_text\">
        ";
        // line 5
        echo _gettext("View output as text");
        // line 6
        echo "    </label>
</li>
";
    }

    public function getTemplateName()
    {
        return "display/export/options_output_radio.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 6,  45 => 5,  40 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "display/export/options_output_radio.twig", "C:\\Users\\FamiliaNatelloMedina\\Documents\\UNLu\\ProgramacionWeb\\phpMyAdmin\\templates\\display\\export\\options_output_radio.twig");
    }
}
