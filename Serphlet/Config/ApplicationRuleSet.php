<?php
namespace Serphlet\Config;

/**
 * The set of Digester rules required to parse a web configuration file
 * (web.xml).
 */
class ApplicationRuleSet extends Serphlet_Phigester_RuleSetBase
{
    public function addRuleInstances(Serphlet_Phigester_Digester $digester)
    {
        $digester->addSetProperties('web-app');

        $digester->addCallMethod('web-app/include-paths/include-path', 'setIncludePath', 2);
        $digester->addCallParam('web-app/include-paths/include-path', 0, 'path');
        $digester->addCallParam('web-app/include-paths/include-path', 1, 'context-relative');

        $digester->addCallMethod('web-app/context-param', 'setInitParameter', 2);
        $digester->addCallParam('web-app/context-param/param-name', 0);
        $digester->addCallParam('web-app/context-param/param-value', 1);

        $digester->addFactoryCreate('web-app/error-page', new \Serphlet\Config\ErrorPageFactory());
        $digester->addSetNext('web-app/error-page', 'addErrorPage');
        $digester->addCallMethod('web-app/error-page/error-code', 'setErrorCode', 0);
        $digester->addCallMethod('web-app/error-page/exception-type', 'setExceptionType', 0);
        $digester->addCallMethod('web-app/error-page/location', 'setLocation', 0);

        $digester->addFactoryCreate('web-app/filter', new \Serphlet\Config\FilterDefFactory());
        $digester->addSetNext('web-app/filter', 'addFilterDef');
        $digester->addCallMethod('web-app/filter/description', 'setDescription', 0);
        $digester->addCallMethod('web-app/filter/display-name', 'setDisplayName', 0);
        $digester->addCallMethod('web-app/filter/filter-class', 'setFilterClass', 0);
        $digester->addCallMethod('web-app/filter/filter-name', 'setFilterName', 0);
        $digester->addCallMethod('web-app/filter/large-icon', 'setLargeIcon', 0);
        $digester->addCallMethod('web-app/filter/small-icon', 'setSmallIcon', 0);
        $digester->addCallMethod('web-app/filter/init-param', 'addInitParameter', 2);
        $digester->addCallParam('web-app/filter/init-param/param-name', 0);
        $digester->addCallParam('web-app/filter/init-param/param-value', 1);

        $digester->addFactoryCreate('web-app/filter-mapping', new \Serphlet\Config\FilterMapsFactory());
        $digester->addSetNext('web-app/filter-mapping', 'addFilterMaps');
        $digester->addCallMethod('web-app/filter-mapping/filter-name', 'setFilterName', 0);
        $digester->addCallMethod('web-app/filter-mapping/servlet-name','addServletName', 0);
        $digester->addCallMethod('web-app/filter-mapping/url-pattern','addURLPattern', 0);
        // TODO:
//        $digester->addCallMethod('web-app/filter-mapping/dispatcher', 'setDispatcher', 0);

        $digester->addFactoryCreate('web-app/servlet', new \Serphlet\Config\ApplicationConfigFactory());
        $digester->addSetNext('web-app/servlet', 'addServletConfig');

        $digester->addCallMethod('web-app/servlet/init-param', 'setInitParameter', 2);
        $digester->addCallParam('web-app/servlet/init-param/param-name', 0);
        $digester->addCallParam('web-app/servlet/init-param/param-value', 1);

        $digester->addCallMethod('web-app/servlet/servlet-name', 'setServletName', 0);
        $digester->addCallMethod('web-app/servlet/servlet-class','setServletClass', 0);

        $digester->addFactoryCreate('web-app/servlet-mapping', new \Serphlet\Config\ServletMapFactory());
        $digester->addSetNext('web-app/servlet-mapping', 'addServletMapping');
        $digester->addCallMethod('web-app/servlet-mapping/servlet-name', 'setServletName', 0);
        $digester->addCallMethod('web-app/servlet-mapping/url-pattern','addUrlPattern', 0);

        $digester->addCallMethod('web-app/welcome-file-list/welcome-file', 'addWelcomeFile', 0);
    }
}

/**
 * An object creation factory which creates filter config instances.
 */
final class FilterDefFactory extends Serphlet_Phigester_AbstractObjectCreationFactory
{
    /**
	 * @param array $attributes
	 * @return object
	 */
    public function createObject(array $attributes)
    {
        $className = 'Serphlet\Config\FilterDef';

        // Instantiate the new object and return it
        $config = null;
        try {
            $config = \Serphlet\ClassLoader\::newInstance($className, 'Serphlet\Config\FilterDef');
        } catch (Exception $e) {
            $this->digester->getLogger()->error('Serphlet\Config\FilterDefFactory->createObject(): ' . $e->getMessage());
        }

        return $config;
    }
}

/**
 * An object creation factory which creates filter maps instances.
 */
final class FilterMapsFactory extends Serphlet_Phigester_AbstractObjectCreationFactory
{
    /**
	 * @param array $attributes
	 * @return object
	 */
    public function createObject(array $attributes)
    {
        $className = 'Serphlet\Config\FilterMaps';

        // Instantiate the new object and return it
        $config = null;
        try {
            $config = \Serphlet\ClassLoader\::newInstance($className, 'Serphlet\Config\FilterMaps');
        } catch (Exception $e) {
            $this->digester->getLogger()->error('Serphlet\Config\FilterMapsFactory->createObject(): ' . $e->getMessage());
        }

        return $config;
    }
}

/**
 * An object creation factory which creates error page instances.
 */
final class ErrorPageFactory extends Serphlet_Phigester_AbstractObjectCreationFactory
{
    /**
	 * @param array $attributes
	 * @return object
	 */
    public function createObject(array $attributes)
    {
        $className = 'Serphlet\Config\ErrorPage';

        // Instantiate the new object and return it
        $config = null;
        try {
            $config = \Serphlet\ClassLoader\::newInstance($className, 'Serphlet\Config\ErrorPage');

        } catch (Exception $e) {
            $this->digester->getLogger()->error('Serphlet\Config\ErrorPageFactory->createObject(): ' . $e->getMessage());
        }

        return $config;
    }
}

/**
 * An object creation factory which creates filter maps instances.
 */
final class ApplicationConfigFactory extends Serphlet_Phigester_AbstractObjectCreationFactory
{
    /**
	 * @param array $attributes
	 * @return object
	 */
    public function createObject(array $attributes)
    {
        $className = 'Serphlet\Config\ApplicationConfig';

        // Instantiate the new object and return it
        $config = null;
        try {
            $config = \Serphlet\ClassLoader\::newInstance($className, 'Serphlet\Config\ServletConfig');

        } catch (Exception $e) {
            $this->digester->getLogger()->error('Serphlet\Config\ApplicationConfig->createObject(): ' . $e->getMessage());
        }

        return $config;
    }
}

/**
 * An object creation factory which creates filter maps instances.
 */
final class ServletMapFactory extends Serphlet_Phigester_AbstractObjectCreationFactory
{
    /**
	 * @param array $attributes
	 * @return object
	 */
    public function createObject(array $attributes)
    {
        $className = 'Serphlet\Config\ServletMap';

        // Instantiate the new object and return it
        $config = null;
        try {
            $config = \Serphlet\ClassLoader\::newInstance($className, 'Serphlet\Config\ServletMap');

        } catch (Exception $e) {
            $this->digester->getLogger()->error('Serphlet\Config\ServletMapFactory->createObject(): ' . $e->getMessage());
        }

        return $config;
    }
}
