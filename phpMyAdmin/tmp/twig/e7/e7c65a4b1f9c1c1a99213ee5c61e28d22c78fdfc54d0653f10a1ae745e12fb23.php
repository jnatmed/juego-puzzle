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

/* checkbox.twig */
class __TwigTemplate_5ac1ec44068da30b1b4ce7ccc106c75a842988d519d8ff52b707f934a2f7cf2e extends Template
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
        echo "<input type=\"checkbox\" name=\"";
        echo twig_escape_filter($this->env, ($context["html_field_name"] ?? null), "html", null, true);
        echo "\"";
        // line 2
        if (array_key_exists("html_field_id", $context)) {
            echo " id=\"";
            echo twig_escape_filter($this->env, ($context["html_field_id"] ?? null), "html", null, true);
            echo "\"";
        }
        // line 3
        if ((array_key_exists("checked", $context) && ($context["checked"] ?? null))) {
            echo " checked=\"checked\"";
        }
        // line 4
        if ((array_key_exists("onclick", $context) && ($context["onclick"] ?? null))) {
            echo " class=\"autosubmit\"";
        }
        echo "><label";
        // line 5
        if (array_key_exists("html_field_id", $context)) {
            echo " for=\"";
            echo twig_escape_filter($this->env, ($context["html_field_id"] ?? null), "html", null, true);
            echo "\"";
        }
        // line 6
        echo ">";
        echo twig_escape_filter($this->env, ($context["label"] ?? null), "html", null, true);
        echo "</label>
";
    }

    public function getTemplateName()
    {
        return "checkbox.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  62 => 6,  56 => 5,  51 => 4,  47 => 3,  41 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "checkbox.twig", "C:\\Users\\FamiliaNatelloMedina\\Documents\\UNLu\\ProgramacionWeb\\phpMyAdmin\\templates\\checkbox.twig");
    }
}
