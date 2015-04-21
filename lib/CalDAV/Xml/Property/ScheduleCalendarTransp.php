<?php

namespace Sabre\CalDAV\Xml\Property;

use Sabre\Xml\Element;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\Element\Elements;
use Sabre\CalDAV\Plugin;

/**
 * schedule-calendar-transp property.
 *
 * This property is a representation of the schedule-calendar-transp property.
 * This property is defined in:
 *
 * http://tools.ietf.org/html/rfc6638#section-9.1
 *
 * Its values are either 'transparent' or 'opaque'. If it's transparent, it
 * means that this calendar will not be taken into consideration when a
 * different user queries for free-busy information. If it's 'opaque', it will.
 *
 * @copyright Copyright (C) 2007-2015 fruux GmbH (https://fruux.com/).
 * @author Evert Pot (http://www.rooftopsolutions.nl/)
 * @license http://sabre.io/license/ Modified BSD License
 */
class ScheduleCalendarTransp implements Element {

    const TRANSPARENT = 'transparent';
    const OPAQUE = 'opaque';

    /**
     * value
     *
     * @var string
     */
    protected $value;

    /**
     * Creates the property
     *
     * @param string $value
     */
    function __construct($value) {

        if ($value !== self::TRANSPARENT && $value !== self::OPAQUE) {
            throw new \InvalidArgumentException('The value must either be specified as "transparent" or "opaque"');
        }
        $this->value = $value;

    }

    /**
     * Returns the current value
     *
     * @return string
     */
    function getValue() {

        return $this->value;

    }

    /**
     * The xmlSerialize metod is called during xml writing.
     *
     * Use the $writer argument to write its own xml serialization.
     *
     * An important note: do _not_ create a parent element. Any element
     * implementing XmlSerializble should only ever write what's considered
     * its 'inner xml'.
     *
     * The parent of the current element is responsible for writing a
     * containing element.
     *
     * This allows serializers to be re-used for different element names.
     *
     * If you are opening new elements, you must also close them again.
     *
     * @param Writer $writer
     * @return void
     */
    function xmlSerialize(Writer $writer) {

        switch($this->value) {
            case self::TRANSPARENT :
                $writer->writeElement('{'.Plugin::NS_CALDAV.'}transparent');
                break;
            case self::OPAQUE :
                $writer->writeElement('{'.Plugin::NS_CALDAV.'}opaque');
                break;
        }

    }

    /**
     * The deserialize method is called during xml parsing.
     *
     * This method is called statictly, this is because in theory this method
     * may be used as a type of constructor, or factory method.
     *
     * Often you want to return an instance of the current class, but you are
     * free to return other data as well.
     *
     * You are responsible for advancing the reader to the next element. Not
     * doing anything will result in a never-ending loop.
     *
     * If you just want to skip parsing for this element altogether, you can
     * just call $reader->next();
     *
     * $reader->parseInnerTree() will parse the entire sub-tree, and advance to
     * the next element.
     *
     * @param Reader $reader
     * @return mixed
     */
    static function xmlDeserialize(Reader $reader) {

        $elems = Elements::xmlDeserialize($reader);

        $value = null;

        foreach($elems as $elem) {
            switch($elem) {
                case '{' . Plugin::NS_CALDAV . '}opaque' :
                    $value = self::OPAQUE;
                    break;
                case '{' . Plugin::NS_CALDAV . '}transparent' :
                    $value = self::TRANSPARENT;
                    break;
            }
        }
        if (is_null($value))
           return null;

        return new self($value);

    }

}
