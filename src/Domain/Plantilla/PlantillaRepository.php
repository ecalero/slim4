<?php
declare(strict_types=1);

namespace App\Domain\Plantilla;

interface PlantillaRepository
{


    /**
     * @return Plantilla[]
     */
    public function findAll(): array;

    /**
     * @param int $IDPLANTILLA
     * @return Plantilla
     * @throws PlantillaNotFoundException
     */
    public function findUserOfId(int $id): Plantilla;

    /**
     * @param int $IDPLANTILLA
     * @return array
     * @throws PlantillaNotFoundException
     */
    public function getUserById(int $IDPLANTILLA): array;

    /* agregando una funcion en el repositorio */
    /**
     * @param int $IDPLANTILLA
     * @return array
     * @throws PlantillaNotFoundException
     */
    public function VerificarLogin(array $PLANTILLA): array;

    /**
     * @param int $IDPLANTILLA
     * @return array
     * @throws PlantillaNotFoundException
     */
    public function crearToken(array $PLANTILLA): array;

    /**
     * @return array
     * @throws PlantillaNotFoundException
     */
    public function getColumnasPlantilla(): array;

    /**
     * @return array
     * @throws PlantillaNotFoundException
     */
     public function getFilasPlantilla(): array;

     /**
      * @return Plantilla
      * @throws PlantillaNotFoundException
      */
      public function nuevaPlantilla(Plantilla $plantilla): Plantilla;

}
