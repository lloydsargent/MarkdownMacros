<?php
/**
 *          Markdown Macros.
 *
 *          Designed to give the power user more flexibility in her designs.
 *          Currently, as Markdown stands, it works well for simple pages.
 *          Unfortunately, as it stands, it doesn't allow you to add classes or
 *          id's to all objects.
 * 
 *          An example would be tables. You can easily create a table. However,
 *          it is nigh impossible to create a left justified and right justified
 *          one on the same page.
 * 
 *          Markdown Macros solves this problem by implement macro substitution.
 *          Just before the page is rendered to HTML, Markdown Macros substitutes
 *          it's user defined text. 
 * 
 * @author  Lloyd Sargent
 * @link    
 * @link    https://github.com/lloydsargent/MarkdownMacros
 * @license http://opensource.org/licenses/MIT The MIT License
 * 
 * Hours having written php code, maybe 4 hours. Heh.
 */
class MarkdownMacros extends AbstractPicoPlugin
{
    const API_VERSION = 2;
    private $currentPagePath;
    private $base_url = "";

    /**
     * Triggered after Pico has prepared the raw file contents for parsing
     *
     * @see DummyPlugin::onContentParsing()
     * @see Pico::parseFileContent()
     * @see DummyPlugin::onContentParsed()
     *
     * @param string &$markdown Markdown contents of the requested page
     *
     * @return void
     */
    public function onContentPrepared(&$markdown)
    {
        $myconfig = $this->getPico()->getConfig();                              // get the configuration list
        $macros_name = $myconfig['custom_macros'];                              // get my custom macros name out of the list
        $macros = $myconfig[$macros_name];                                      // get my list of custom macros

        //----- build an array of key:value pairs representing the macros
        $replacement_list = $this->createReplacementList($markdown, $macros);

        //----- replace the macro with the substitute text
        $this->performSubstitution($markdown, $macros, $replacement_list);

        return;        
    }



    /**
     * @fn      createReplacementList
     *
     * @brief   createReplacementList searches through the list for our macros. 
     *          It then creates an array of macro name: character position.
     *          Finally it sorts in descending order (last is first).
     * 
     *          The idea is that we don't traverse the entire text search using
     *          grep.
     * 
     * @param   $original_text - orignal markdown text
     * @param   $macro_list - a list of key:value macros
     * 
     * @returns array of key:value pairs
     */
    private function createReplacementList($original_text, $macro_list) {
        $result = [];
        $offset = 0;
        foreach ($macro_list as $macro => $replacement) {
//            $start_of_macro = rtrim($macro, ']');                             // too simple, matched short strings
            $start_of_macro = $macro;
            while ($offset < strlen($original_text)) {
                $temp = NULL;
                $offset = strpos($original_text, $start_of_macro, $offset);
                if ($offset == false) {
                    break;
                }
                $temp[$macro] = $offset;
                $offset = $offset + 1;
                array_push($result, $temp);                                     // store the dictionary item
            }
        }

        //----- sort by descending order
        usort($result, 'MarkdownMacros::descendingArray');

        return $result;
    }



    /**
     * @fn      descendingArray
     * 
     * @param    $first - dictionary item
     * @param   $second - dictionary item
     */
    private static function descendingArray($first, $second) {
        //----- get the value for the key:value pairs
        $val1 = current($first);                                                // get the value of $first
        $val2 = current($second);                                               // get the value of $second
        if ($val1 == $val2) {
            return 0;
        }
        if ($val1 > $val2) {
            return -1;
        }
        return 1;
    }



    /**
     * @fn      performSubstitution
     * 
     * @details 
     * 
     * @returns 
     */
    private function performSubstitution(&$markdown, $macros, $replacement_list) {
        //----- now go through the replacement_list and substitute our macro's text
        foreach ($replacement_list as $replacement_item) {
            $replacement_text = $macros[key($replacement_item)];
            $markdown = substr_replace($markdown, $replacement_text, current($replacement_item), strlen(key($replacement_item)));
        }
    }
}