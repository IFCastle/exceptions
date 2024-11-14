<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Framework\TestCase;

class CompositeExceptionTest extends TestCase
{
    public function test(): void
    {
        $line                       = __LINE__ + 2;
        $exception = new CompositeException('',
            new UnexpectedValueType('name', 'value', 'expected'),
            new UnexpectedValueType('name', 'value', 'expected'),
        );

        $this->assertEquals('Multiple exceptions occurred', $exception->getMessage());

        $this->assertSame([
            'exceptions' =>
                [
                    0 =>
                        [
                            'type'     => UnexpectedValueType::class,
                            'source'   =>
                                [
                                    'source'   => self::class,
                                    'type'     => '->',
                                    'function' => 'test',
                                ],
                            'file'     => __FILE__,
                            'line'     => $line,
                            'message'  => '',
                            'template' => 'Unexpected type occurred for the value {name} and type {type}. Expected {expected}',
                            'tags'     =>
                                [],
                            'code'     => 0,
                            'data'     =>
                                [
                                    'name'     => 'name',
                                    'type'     => 'STRING',
                                    'expected' => 'expected',
                                ],
                            'previous' => null,
                        ],
                    1 =>
                        [
                            'type'     => UnexpectedValueType::class,
                            'source'   =>
                                [
                                    'source'   => self::class,
                                    'type'     => '->',
                                    'function' => 'test',
                                ],
                            'file'     => __FILE__,
                            'line'     => $line + 1,
                            'message'  => '',
                            'template' => 'Unexpected type occurred for the value {name} and type {type}. Expected {expected}',
                            'tags'     =>
                                [],
                            'code'     => 0,
                            'data'     =>
                                [
                                    'name'     => 'name',
                                    'type'     => 'STRING',
                                    'expected' => 'expected',
                                ],
                            'previous' => null,
                        ],
                ],
        ], $exception->getExceptionData());
    }
}
