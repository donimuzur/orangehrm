<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RawLabelCellHeader
 *
 * @author Muhamamd Zulfi Rusdani
 */
class RawLabelCellHeader extends ListHeader {

    public function __construct() {
        $this->elementTypes[] = 'rawLabel';
    }

}