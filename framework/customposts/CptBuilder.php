<?php

namespace Framework\Customposts;

use \Framework\Customposts\Cpt;
use \Framework\Customposts\CptMeta;
use \Framework\Customposts\CustomTaxonomyBuilder;

class CptBuilder
{
    public function createPostType(array $cpt)
    {
        $builder = new Cpt;
        $meta = new CptMeta;
        $tax_builder = new CustomTaxonomyBuilder;

        $builder->setRoles($cpt);
        $builder->register($cpt);

        if (isset($cpt['custom_taxonomies'])) {
            $tax_builder->registerTaxonomies($cpt);
        }

        // list all meta keys

        if (isset($cpt['fields'])) {
            $fields = $cpt['fields'];
            $meta->register($fields);
            $meta->addMetaBoxes($fields, $cpt['singular_name']);
            $meta->save($fields);
        }
    }
}
