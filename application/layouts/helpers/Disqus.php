<?php
/**
 * @category Application
 * @package  Core_View
 * @subpackage Helper
 */
class Application_View_Helper_Disqus extends Zend_View_Helper_Abstract
{
    public  $enabled = false;

    /**
     * @param array $inlineOptions
     * @return string
     */
    public function disqus(array $inlineOptions)
    {
        $options = $this->_getOptions($inlineOptions);

        $result = '';
        if ($this->enabled) {
            $result = "<div id='disqus_thread'></div>
            <script type='text/javascript'>
                //CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE
                // required: replace example with your forum shortname
                var disqus_shortname = '$options[disqus_shortname]';
                var disqus_developer = $options[disqus_developer];
                var disqus_identifier = '$options[disqus_identifier]';

                /* * * DON'T EDIT BELOW THIS LINE * * */
                (function() {
                    var dsq = document.createElement('script');
                    dsq.type = 'text/javascript'; dsq.async = true;
                    dsq.src = 'http://'
                        + disqus_shortname
                        + '.disqus.com/embed.js';
                    (document.getElementsByTagName('head')[0]
                    || document.getElementsByTagName('body')[0]).appendChild(dsq);
                })();
            </script>
            <noscript>
                Please enable JavaScript to view the
                <a href='http://disqus.com/?ref_noscript'>
                    comments powered by Disqus.
                </a>
            </noscript>
            <a href='http://disqus.com'
               class='dsq-brlink'>
               comments powered by <span class='logo-disqus'>Disqus</span>
           </a>";
        }

        return $result;
    }

    /**
     * @param array $inlineOptions
     * @return array|mixed
     */
    protected function _getOptions(array $inlineOptions)
    {
        $options = $this->_getDefaultOptions();
        $registry = Zend_Registry::getInstance();
        if ($registry->isRegistered('disqus')) {
            $disqus = $registry->get('disqus');
            $registryOptions = array();
            if (isset($disqus['enabled'])) {
                $this->enabled = $disqus['enabled'];
            }
            if (isset($disqus['options'])) {
                $registryOptions = $disqus['options'];
            }
            if (!empty($inlineOptions)) {
                $options = array_merge(
                    $options,
                    $registryOptions,
                    $inlineOptions
                );
            } else {
                $options = $registryOptions;
            }
        }
        return $options;
    }

    /**
     * @return array
     */
    protected function _getDefaultOptions()
    {
        $result = array(
            'disqus_shortname' => 'buyamtest',
            'disqus_developer' => 1
        );
        return $result;
    }
}
