<?php

namespace Framework;

use Framework\Cpt;
use Framework\CptMeta;
use Framework\CustomTaxonomyBuilder;

class CptBuilder
{
    public function createPostType(array $cpt) {
        $builder = new Cpt;
        $meta = new CptMeta;
        $tax_builder = new CustomTaxonomyBuilder;

        $builder->setRoles($cpt);
        $builder->registerPost($cpt);

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
