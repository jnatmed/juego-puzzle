<?php 

return array(
    'infoImg' => array(
            'imgs' => [
                    'img-1' => 'public\imgs\salon-sofa-rosa-4.jpg',
                    'chimenea' => 'public\imgs\chimeneaa-minimalista.jpg',
                    'ciudad' => 'public\imgs\ciudad-in-block.jpg',
                    'insecto' => 'public\imgs\insecto.jpg',
                    'vista-edificio' => 'public\imgs\vista-edificio.jpg',
                    'amsterdan' => 'public\imgs\amsterdan.jpg',
                    'montaña' => 'public\imgs\montaña.jpg',
                    'pratiwi' => 'public\imgs\pratiwi.jpg',
                    'loto' => 'public\imgs\loto.jpg'
                ],
            'dimensiones' => ['ANCHO' => '450',
                              'ALTO'  => '450',
                              'TAMANIO_PIEZA' => '150']),
    /**
     * aqui guardo los formatos para las diferentes 
     * dimensiones de puzzle, osea 3X3 4X4 5X5
     * donde los valores se van adecuando de acuerdo al
     * tamaño de puzzle: es decir
     * las imagenes se redimensionan a un tamaño 
     * en el que al recortarlas no queden pixeles libres
     * Eje: 
     * 3X3 => va a tener cada imagen un tamaño de (630px X 630px) => lo que me da son piezas de (210x210)
     * 4X4 => va a tener cada imagen un tamaño de (620px X 620px) => lo que me da son piezas de (155x155) 
     * 5X5 => va a tener cada imagen un tamaño de (630px X 630px) => lo que me da son piezas de (126x126)
     * 
     * y asi, en general se busca dimensiones con valores divisibles. 
     */
    'matriz' => array(['x' => 0, 'y' => 0],
                      ['x' => 150, 'y' => 0],
                      ['x' => 300, 'y' => 0],
                      ['x' => 0, 'y' => 150],
                      ['x' => 150, 'y' => 150],
                      ['x' => 300, 'y' => 150],
                      ['x' => 0, 'y' => 300],
                      ['x' => 150, 'y' => 300],
                      ['x' => 300, 'y' => 300]),
                      
    'matriz_2' => array( 
                        '3x3' => array(['x' => 0, 'y' => 0],
                                       ['x' => 150, 'y' => 0],
                                       ['x' => 300, 'y' => 0],
                                       ['x' => 0, 'y' => 150],
                                       ['x' => 150, 'y' => 150],
                                       ['x' => 300, 'y' => 150],
                                       ['x' => 0, 'y' => 300],
                                       ['x' => 150, 'y' => 300],
                                       ['x' => 300, 'y' => 300]),
                        '4x4' => array(['x' => 0, 'y' => 0],
                                       ['x' => 150, 'y' => 0],
                                       ['x' => 300, 'y' => 0],
                                       ['x' => 0, 'y' => 150],
                                       ['x' => 150, 'y' => 150],
                                       ['x' => 300, 'y' => 150],
                                       ['x' => 0, 'y' => 300],
                                       ['x' => 150, 'y' => 300],
                                       ['x' => 300, 'y' => 300]),
                        '5x5' => array(['x' => 0, 'y' => 0],
                                       ['x' => 150, 'y' => 0],
                                       ['x' => 300, 'y' => 0],
                                       ['x' => 0, 'y' => 150],
                                       ['x' => 150, 'y' => 150],
                                       ['x' => 300, 'y' => 150],
                                       ['x' => 0, 'y' => 300],
                                       ['x' => 150, 'y' => 300],
                                       ['x' => 300, 'y' => 300]),

                        )
 
) 



?>