<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* @Common/HelpBox/helpbox.html.twig */
class __TwigTemplate_1e96e732937fdaadd8daca2a3d4b55ddbc3c945c11d82f4c080d92246113aea5 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 25
        echo "
 <span
   class=\"help-box";
        // line 27
        if ((isset($context["classes"]) || array_key_exists("classes", $context))) {
            echo " ";
            echo twig_escape_filter($this->env, ($context["classes"] ?? null), "html", null, true);
        }
        echo "\"
   data-toggle=\"popover\"
   data-trigger=\"hover\"
   data-html=\"true\"
   ";
        // line 31
        if ((isset($context["content"]) || array_key_exists("content", $context))) {
            // line 32
            echo "     data-content=\"";
            echo twig_escape_filter($this->env, ($context["content"] ?? null), "html", null, true);
            echo "\"
   ";
        }
        // line 34
        echo "
   ";
        // line 35
        if ((isset($context["placement"]) || array_key_exists("placement", $context))) {
            // line 36
            echo "     data-placement=\"";
            echo twig_escape_filter($this->env, ($context["placement"] ?? null), "html", null, true);
            echo "\"
   ";
        }
        // line 38
        echo "
   ";
        // line 39
        if ((isset($context["title"]) || array_key_exists("title", $context))) {
            // line 40
            echo "     data-title=\"";
            echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
            echo "\"
   ";
        }
        // line 42
        echo " >
 </span>
";
    }

    public function getTemplateName()
    {
        return "@Common/HelpBox/helpbox.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  74 => 42,  68 => 40,  66 => 39,  63 => 38,  57 => 36,  55 => 35,  52 => 34,  46 => 32,  44 => 31,  34 => 27,  30 => 25,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@Common/HelpBox/helpbox.html.twig", "C:\\webs\\prestashop\\src\\PrestaShopBundle\\Resources\\views\\Admin\\Common\\HelpBox\\helpbox.html.twig");
    }
}
