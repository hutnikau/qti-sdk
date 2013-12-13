<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2013 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts, <jerome@taotesting.com>
 * @license GPLv2
 * @package qtism
 * @subpackage
 *
 */

namespace qtism\runtime\rendering\markup\xhtml;

use qtism\runtime\rendering\AbstractRenderingContext;
use qtism\data\QtiComponent;
use \DOMDocumentFragment;

/**
 * PositionObjectInteraction renderer. Rendered components will be transformed as 
 * 'div' elements with a 'qti-positionObjectInteraction' additional CSS class.
 * 
 * The following data-X attributes will be rendered:
 * 
 * * data-responseIdentifier = qti:interaction->responseIdentifier
 * * data-maxChoices = qti:positionObjectInteraction->maxChoices
 * * data-minChoices = qti:positionObjectInteraction->minChoices (Rendered only if a value is present for the attribute)
 * * data-centerPoint = qti:positionObjectInteraction->centerPoint (Rendered only if a value is present for the attribute) 
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class PositionObjectInteractionRenderer extends InteractionRenderer {
    
    public function __construct(AbstractRenderingContext $renderingContext = null) {
        parent::__construct($renderingContext);
        $this->transform('div');
    }
    
    protected function appendAttributes(DOMDocumentFragment $fragment, QtiComponent $component) {
        
        parent::appendAttributes($fragment, $component);
        $this->additionalClass('qti-positionObjectInteraction');
        
        $fragment->firstChild->setAttribute('data-maxChoices', $component->getMaxChoices());
        
        if ($component->hasMinChoices() === true) {
            $fragment->firstChild->setAttribute('data-minChoices', $component->getMinChoices());
        }
        
        if ($component->hasCenterPoint() === true) {
            $fragment->firstChild->setAttribute('data-centerPoint', $component->getCenterPoint()->getX() . " " . $component->getCenterPoint()->getY());
        }
    }
}