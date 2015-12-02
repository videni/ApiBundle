<?php

namespace Oro\Component\EntitySerializer\Tests\Unit;

class SimpleEntitySerializerTest extends EntitySerializerTestCase
{
    public function testSimpleEntityWithoutConfig()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0, o0_.name AS name_1, o0_.label AS label_2'
            . ', o0_.public AS public_3, o0_.is_exception AS is_exception_4'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'           => 1,
                    'name_1'         => 'test_name',
                    'label_2'        => 'test_label',
                    'public_3'       => 1,
                    'is_exception_4' => 0
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize($qb, []);

        $this->assertArrayEquals(
            [
                [
                    'id'          => 1,
                    'name'        => 'test_name',
                    'label'       => 'test_label',
                    'public'      => true,
                    'isException' => false
                ]
            ],
            $result
        );
    }

    public function testSimpleEntityWithExclusion()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0, o0_.label AS label_1, o0_.public AS public_2'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'     => 1,
                    'label_1'  => 'test_label',
                    'public_2' => 1,
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'fields' => [
                    'name'        => ['exclude' => true],
                    'isException' => ['exclude' => true],
                ]
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id'     => 1,
                    'label'  => 'test_label',
                    'public' => true
                ]
            ],
            $result
        );
    }

    /**
     * @deprecated since 1.9. Use 'exclude' attribute for a field instead of 'excluded_fields' for an entity
     */
    public function testSimpleEntityWithExclusionDeprecated()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0, o0_.label AS label_1, o0_.public AS public_2'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'     => 1,
                    'label_1'  => 'test_label',
                    'public_2' => 1,
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'excluded_fields' => ['name', 'isException'],
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id'     => 1,
                    'label'  => 'test_label',
                    'public' => true
                ]
            ],
            $result
        );
    }

    public function testSimpleEntityWithExclusionAndPartialLoadDisabled()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0, o0_.name AS name_1, o0_.label AS label_2'
            . ', o0_.public AS public_3, o0_.is_exception AS is_exception_4'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'           => 1,
                    'name_1'         => 'test_name',
                    'label_2'        => 'test_label',
                    'public_3'       => 1,
                    'is_exception_4' => 0
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'disable_partial_load' => true,
                'fields'               => [
                    'name'        => [
                        'exclude' => true
                    ],
                    'isException' => [
                        'exclude' => true
                    ]
                ]
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id'     => 1,
                    'label'  => 'test_label',
                    'public' => true
                ]
            ],
            $result
        );
    }

    /**
     * @deprecated since 1.9. Use 'exclude' attribute for a field instead of 'excluded_fields' for an entity
     */
    public function testSimpleEntityWithExclusionDeprecatedAndPartialLoadDisabled()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0, o0_.name AS name_1, o0_.label AS label_2'
            . ', o0_.public AS public_3, o0_.is_exception AS is_exception_4'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'           => 1,
                    'name_1'         => 'test_name',
                    'label_2'        => 'test_label',
                    'public_3'       => 1,
                    'is_exception_4' => 0
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'excluded_fields'      => ['name', 'isException'],
                'disable_partial_load' => true
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id'     => 1,
                    'label'  => 'test_label',
                    'public' => true
                ]
            ],
            $result
        );
    }

    public function testSimpleEntityWithSpecifiedFieldsButNoExclusionPolicy()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0, o0_.name AS name_1, o0_.label AS label_2'
            . ', o0_.public AS public_3, o0_.is_exception AS is_exception_4'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'           => 1,
                    'name_1'         => 'test_name',
                    'label_2'        => null,
                    'public_3'       => 0,
                    'is_exception_4' => 0
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'fields' => [
                    'id'   => null,
                    'name' => null,
                ],
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id'          => 1,
                    'name'        => 'test_name',
                    'label'       => null,
                    'public'      => false,
                    'isException' => false
                ]
            ],
            $result
        );
    }

    public function testSimpleEntityWithSpecifiedFieldsOnly()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0, o0_.name AS name_1'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'   => 1,
                    'name_1' => 'test_name',
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'exclusion_policy' => 'all',
                'fields'           => [
                    'id'   => null,
                    'name' => null,
                ],
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id'   => 1,
                    'name' => 'test_name',
                ]
            ],
            $result
        );
    }

    public function testSimpleEntityWithSpecifiedFieldsAndExclusions()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0' => 1,
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'exclusion_policy' => 'all',
                'fields'           => [
                    'id'   => null,
                    'name' => [
                        'exclude' => true
                    ],
                ],
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id' => 1,
                ]
            ],
            $result
        );
    }

    /**
     * @deprecated since 1.9. Use 'exclude' attribute for a field instead of 'excluded_fields' for an entity
     */
    public function testSimpleEntityWithSpecifiedFieldsAndExclusionsDeprecated()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0' => 1,
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'exclusion_policy' => 'all',
                'excluded_fields'  => ['name'],
                'fields'           => [
                    'id'   => null,
                    'name' => null,
                ],
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id' => 1,
                ]
            ],
            $result
        );
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testSimpleEntityWithPostAction()
    {
        $qb = $this->em->getRepository('Test:User')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $conn = $this->getDriverConnectionMock($this->em);

        $this->setQueryExpectationAt(
            $conn,
            0,
            'SELECT o0_.id AS id_0, o0_.name AS name_1,'
            . ' o1_.name AS name_2,'
            . ' o0_.category_name AS category_name_3'
            . ' FROM oro_test_serializer_user o0_'
            . ' LEFT JOIN oro_test_serializer_category o1_ ON o0_.category_name = o1_.name'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'            => 1,
                    'name_1'          => 'user_name',
                    'name_2'          => 'category_name',
                    'category_name_3' => 'category_name'
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $this->setQueryExpectationAt(
            $conn,
            1,
            'SELECT o0_.id AS id_0, o1_.name AS name_1'
            . ' FROM oro_test_serializer_product o1_'
            . ' INNER JOIN oro_test_serializer_user o0_ ON (o1_.owner_id = o0_.id)'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'   => 1,
                    'name_1' => 'product_name'
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'exclusion_policy' => 'all',
                'fields'           => [
                    'id'       => null,
                    'name'     => null,
                    'category' => [
                        'exclusion_policy' => 'all',
                        'fields'           => [
                            'name' => null
                        ],
                        'post_serialize'   => function (array $result) {
                            $result['additional'] = $result['name'] . '_additional';

                            return $result;
                        }
                    ],
                    'products' => [
                        'exclusion_policy' => 'all',
                        'fields'           => [
                            'name' => null
                        ],
                        'post_serialize'   => function (array $result) {
                            $result['additional'] = $result['name'] . '_additional';

                            return $result;
                        }
                    ],
                ],
                'post_serialize'   => function (array $result) {
                    $result['additional'] = $result['name'] . '_additional';

                    return $result;
                }
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id'         => 1,
                    'name'       => 'user_name',
                    'category'   => [
                        'name'       => 'category_name',
                        'additional' => 'category_name_additional'
                    ],
                    'products'   => [
                        [
                            'name'       => 'product_name',
                            'additional' => 'product_name_additional'
                        ]
                    ],
                    'additional' => 'user_name_additional'
                ]
            ],
            $result
        );
    }

    public function testPostActionForNullChild()
    {
        $qb = $this->em->getRepository('Test:User')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $conn = $this->getDriverConnectionMock($this->em);

        $this->setQueryExpectationAt(
            $conn,
            0,
            'SELECT o0_.id AS id_0, o0_.name AS name_1,'
            . ' o1_.name AS name_2,'
            . ' o0_.category_name AS category_name_3'
            . ' FROM oro_test_serializer_user o0_'
            . ' LEFT JOIN oro_test_serializer_category o1_ ON o0_.category_name = o1_.name'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'            => 1,
                    'name_1'          => 'user_name',
                    'name_2'          => null,
                    'category_name_3' => null
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'exclusion_policy' => 'all',
                'fields'           => [
                    'id'       => null,
                    'name'     => null,
                    'category' => [
                        'exclusion_policy' => 'all',
                        'fields'           => [
                            'name' => null
                        ],
                        'post_serialize'   => function (array $result) {
                            $result['additional'] = $result['name'] . '_additional';

                            return $result;
                        }
                    ],
                ]
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id'       => 1,
                    'name'     => 'user_name',
                    'category' => null
                ]
            ],
            $result
        );
    }

    /**
     * @deprecated since 1.9. Use new signature of 'post_serialize' callback:
     * function (array $item) : array
     */
    public function testSimpleEntityWithPostActionDeprecated()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0, o0_.name AS name_1, o0_.label AS label_2'
            . ', o0_.public AS public_3, o0_.is_exception AS is_exception_4'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'           => 1,
                    'name_1'         => 'test_name',
                    'label_2'        => 'test_label',
                    'public_3'       => 1,
                    'is_exception_4' => 0
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'post_serialize' => function (array &$result) {
                    $result['additional'] = $result['name'] . '_additional';
                }
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id'          => 1,
                    'name'        => 'test_name',
                    'label'       => 'test_label',
                    'public'      => true,
                    'isException' => false,
                    'additional'  => 'test_name_additional'
                ]
            ],
            $result
        );
    }

    public function testSimpleEntityWithMetadata()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0, o0_.label AS label_1'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'    => 1,
                    'label_1' => 'test_label'
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'fields' => [
                    'entity'      => [
                        'property_path' => '__class__'
                    ],
                    'name'        => [
                        'exclude' => true
                    ],
                    'public'      => [
                        'exclude' => true
                    ],
                    'isException' => [
                        'exclude' => true
                    ]
                ]
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id'     => 1,
                    'label'  => 'test_label',
                    'entity' => 'Oro\Component\EntitySerializer\Tests\Unit\Fixtures\Entity\Group'
                ]
            ],
            $result
        );
    }

    /**
     * @deprecated since 1.9. Use 'exclude' attribute for a field instead of 'excluded_fields' for an entity
     * @deprecated since 1.9. Use `property_path` attribute instead of 'result_name'
     */
    public function testSimpleEntityWithMetadataDeprecated()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0, o0_.label AS label_1'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'    => 1,
                    'label_1' => 'test_label'
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'excluded_fields' => ['name', 'public', 'isException'],
                'fields'          => [
                    '__class__' => [
                        'result_name' => 'entity'
                    ]
                ]
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id'     => 1,
                    'label'  => 'test_label',
                    'entity' => 'Oro\Component\EntitySerializer\Tests\Unit\Fixtures\Entity\Group'
                ]
            ],
            $result
        );
    }

    public function testSimpleEntityWithMetadataAndExcludeAllPolicy()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0, o0_.label AS label_1'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'    => 1,
                    'label_1' => 'test_label'
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'exclusion_policy' => 'all',
                'fields'           => [
                    'id'     => null,
                    'label'  => null,
                    'entity' => [
                        'property_path' => '__class__'
                    ]
                ]
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id'     => 1,
                    'label'  => 'test_label',
                    'entity' => 'Oro\Component\EntitySerializer\Tests\Unit\Fixtures\Entity\Group'
                ]
            ],
            $result
        );
    }

    /**
     * @deprecated since 1.9. Use `property_path` attribute instead of 'result_name'
     */
    public function testSimpleEntityWithMetadataAndExcludeAllPolicyDeprecated()
    {
        $qb = $this->em->getRepository('Test:Group')->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', 1);

        $this->setQueryExpectation(
            $this->getDriverConnectionMock($this->em),
            'SELECT o0_.id AS id_0, o0_.label AS label_1'
            . ' FROM oro_test_serializer_group o0_'
            . ' WHERE o0_.id = ?',
            [
                [
                    'id_0'    => 1,
                    'label_1' => 'test_label'
                ]
            ],
            [1 => 1],
            [1 => \PDO::PARAM_INT]
        );

        $result = $this->serializer->serialize(
            $qb,
            [
                'exclusion_policy' => 'all',
                'fields'           => [
                    'id'        => null,
                    'label'     => null,
                    '__class__' => [
                        'result_name' => 'entity'
                    ]
                ]
            ]
        );

        $this->assertArrayEquals(
            [
                [
                    'id'     => 1,
                    'label'  => 'test_label',
                    'entity' => 'Oro\Component\EntitySerializer\Tests\Unit\Fixtures\Entity\Group'
                ]
            ],
            $result
        );
    }
}
