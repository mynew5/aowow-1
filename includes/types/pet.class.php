<?php

if (!defined('AOWOW_REVISION'))
    die('illegal access');


class PetList extends BaseType
{
    use ListviewHelper;

    public static $type      = TYPE_PET;
    public static $brickFile = 'pet';

    protected     $queryBase = 'SELECT *, id AS ARRAY_KEY FROM ?_pet p';

    public function getListviewData()
    {
        $data = [];

        foreach ($this->iterate() as $__)
        {
            $data[$this->id] = array(
                'armor'    => $this->curTpl['armor'],
                'damage'   => $this->curTpl['damage'],
                'health'   => $this->curTpl['health'],
                'diet'     => $this->curTpl['foodMask'],
                'icon'     => $this->curTpl['iconString'],
                'id'       => $this->id,
                'maxlevel' => $this->curTpl['maxLevel'],
                'minlevel' => $this->curTpl['minLevel'],
                'name'     => $this->getField('name', true),
                'type'     => $this->curTpl['type'],
                'exotic'   => $this->curTpl['exotic'],
                'spells'   => []
            );

            if ($this->curTpl['expansion'] > 0)
                $data[$this->id]['expansion'] = $this->curTpl['expansion'];

            for ($i = 1; $i <= 4; $i++)
                if ($this->curTpl['spellId'.$i] > 0)
                    $data[$this->id]['spells'][] = $this->curTpl['spellId'.$i];
        }

        return $data;
    }

    public function addGlobalsToJScript($addMask = GLOBALINFO_ANY)
    {
        foreach ($this->iterate() as $__)
        {
            if ($addMask & GLOBALINFO_RELATED)
                for ($i = 1; $i <= 4; $i++)
                    if ($this->curTpl['spellId'.$i] > 0)
                        Util::$pageTemplate->extendGlobalIds(TYPE_SPELL, $this->curTpl['spellId'.$i]);

            if ($addMask & GLOBALINFO_SELF)
                Util::$pageTemplate->extendGlobalData(self::$type, [$this->id => ['icon' => $this->curTpl['iconString']]]);
        }
    }

    public function renderTooltip() { }
}

?>
