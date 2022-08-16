# Image manager for symfony

## Description:

Component used for make one way for persist information about image to
database and store them to filesystem.

## Installation

    composer require xaduken/image-symfony-support

## Usage:

Suppose you have entity User. This entity need have avatar, then you create
for example Image entity and make it implements Imageable interface.
In this way you guarantee Image table in database will have needle
columns.

### Image entity may be something like this:

    <?php

    namespace App\Entity;

    use App\Repository\ImageRepository;
    use DateTimeImmutable;
    use Doctrine\ORM\Mapping as ORM;
    use Xaduken\ImageSupport\EntityInterface\Imageable;

    #[ORM\Entity(repositoryClass: ImageRepository::class)]
    #[ORM\HasLifecycleCallbacks]
    class Image implements Imageable
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 255)]
        private ?string $related_entity = null;

        #[ORM\Column(length: 400, nullable: true)]
        private ?string $path = null;

        #[ORM\Column(length: 255)]
        private ?string $fileName = null;

        #[ORM\Column(length: 255)]
        private ?string $mimeType = null;

        #[ORM\Column]
        private ?DateTimeImmutable $createdAt = null;

        #[ORM\Column]
        private ?DateTimeImmutable $updatedAt = null;

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getRelatedEntity(): ?string
        {
            return $this->related_entity;
        }

        public function setRelatedEntity(string $related_entity): self
        {
            $this->related_entity = $related_entity;

            return $this;
        }

        public function getPath(): ?string
        {
            return $this->path;
        }

        public function setPath(?string $path): self
        {
            $this->path = $path;

            return $this;
        }

        public function getFileName(): ?string
        {
            return $this->fileName;
        }

        public function setFileName(string $fileName): self
        {
            $this->fileName = $fileName;

            return $this;
        }

        public function getMimeType(): ?string
        {
            return $this->mimeType;
        }

        public function setMimeType(string $mimeType): self
        {
            $this->mimeType = $mimeType;

            return $this;
        }

        public function getCreatedAt(): ?DateTimeImmutable
        {
            return $this->createdAt;
        }

        public function setCreatedAt(DateTimeImmutable $createdAt): self
        {
            $this->createdAt = $createdAt;

            return $this;
        }

        public function getUpdatedAt(): ?DateTimeImmutable
        {
            return $this->updatedAt;
        }

        public function setUpdatedAt(DateTimeImmutable $updatedAt): self
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }

        #[ORM\PreUpdate]
        public function onUpdate(): void
        {
            $this->updatedAt = new DateTimeImmutable();
        }

        #[ORM\PrePersist]
        public function onCreate(): void
        {
            $this->createdAt = new DateTimeImmutable();
            $this->updatedAt = new DateTimeImmutable();
        }
    }

How you can see, Image entity impelemts Imageable interface.
It`s required.

After that you need to create UserImageManager and extend it by
AbstractImageManager.

### UserImageManager:

    <?php

    namespace App\Image;

    use App\Entity\Image;
    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Xaduken\ImageSupport\Factory\ImageManagerFactoryInterface;
    use Xaduken\ImageSupport\Factory\SimpleImageManagerFactory;
    use Xaduken\ImageSupport\Service\AbstractImageManager;

    class UserImageManager extends AbstractImageManager
    {
        private EntityManagerInterface $entityManager;

        private string $targetDir;

        public function __construct(EntityManagerInterface $entityManager, string $targetDir)
        {
            $this->entityManager = $entityManager;
            $this->targetDir = $targetDir;
        }

        protected function getTargetDirectory(): string
        {
            return $this->targetDir;
        }

        protected function getRelatedClass(): string
        {
            return User::class;
        }

        protected function getImageManagerFactory(): ImageManagerFactoryInterface
        {
            return new SimpleImageManagerFactory($this->getEntityManager());
        }

        protected function getEntityManager(): EntityManagerInterface
        {
            return $this->entityManager;
        }

        protected function getImageEntityClass(): string
        {
            return Image::class;
        }
    }

Let`s look more detail on methods. 

### getTargetDirectory()
Used for define path, where images for user should be stored.
In this case we push it to constructor and transfer it via property.
You may use any others way to do it.

### getRelatedClass()
Push here full class name which should be stored in related_class
column in table references to defined entity in getImageEntityClass
method.

### getImageManagerFactory()
Push here class instance of ImageManagerFactoryInterface. See full
list of available factories on end of this file.

### getEntityManager()
Push here doctrine entityManager. In this example we get it from 
constructor. In usage example we get it from dependency injection.

### getImageEntityClass()
Push fully class name of entity implementation Imageable interface.
In this case is Image Entity. 

## Testing:

After create UserImageManager we ready to store our files in database 
and filesystem used one simple class!

### See example: 

    <?php

    namespace App\Controller\Api;

    use App\Image\UserImageManager;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class TestController extends AbstractController
    {
        #[Route(path: '/api/test-image', name: 'test-image')]
        public function testImage(Request $request, UserImageManager $userImageManager): Response
        {
            $image = $request->files->get('image');

            $result = $userImageManager->save($image);

            dd(result);
        }
    }

We get UserImageManager via dependency injection 
and use just one method for save image to database and filesystem 
and manage his relations!

But maybe you ask how UserImageManager class get $targetDirectory property?
Very simple. just use symfony manually injection.

### /config/services.yaml
    parameters:
        ...
        user_image_directory: '%kernel.project_dir%/public/uploads/user'
    services:
        ...
        App\Image\UserImageManager:
            arguments:
                $targetDir: '%user_image_directory%'

And that's it! Now if you need save image related to User entity.
Just get UserEntityManager by dependency injection and save your image.

## Factories
This is all cool, but that if you need resize image before save it 
to filesystem? All some another things? To answer this questions, 
I use abstract factory pattern. 

If you need define costume way for 
this, create your own factory, implemented by ImageManagerFactoryInterface
and push your implementation to UserImageManger.getImageManagerFactory()
method.

**ImageManagerFactoryInterface have very simple signature, lets look**

### ImageManagerFactoryInterface:

    <?php

    namespace Xaduken\ImageSupport\Factory;

    use Xaduken\ImageSupport\Database\DatabaseImageUploaderInterface;
    use Xaduken\ImageSupport\Filesystem\FilesystemUploaderInterface;

    interface ImageManagerFactoryInterface
    {
        public function getDatabaseUploader(): DatabaseImageUploaderInterface;

        public function getFilesystemUploader(): FilesystemUploaderInterface;
    }

### Let`s see more detail on methods

### getDatabaseUploader()

Should return class instance of implementation DatabaseImageUploaderInterface.
You should create this implementation. This class should 
define way, how persist Imageable entity to database. For simple 
persisting use DatabaseImageUploader class instance.


We discuss how create implementation of DatabaseUploaderInterface later.

### getFilesystemUploader()

Should return class instance of implementation FilesystemUploaderInterface.
You also should make implementation by self or use StandardFilesystemUploader 
if you need simple save.

Just like with DatabaseImageUploaderInterface we discuss how make 
implementation later.

### DatabaseImageUploaderInterface

they signature so simple:

    <?php

    namespace Xaduken\ImageSupport\Database;

    use Xaduken\ImageSupport\DTO\ImageInfo;
    use Xaduken\ImageSupport\EntityInterface\Imageable;

    interface DatabaseImageUploaderInterface
    {
        public function upload(ImageInfo $imageInfo, string $relatedEntity, string $imageClass): Imageable;
    }

method upload should save Image to database.
- $imageInfo - is simple DTO class, it will be returned after save image to filesystem.
- $relatedEntity - use it to set $relatedEntity to Imageamble entity.
- $imageClass - create Imageable object by this property.

### FilesystemUploaderInterface

they signature very familiar with DatabaseImageUploaderInterface

    <?php

    namespace Xaduken\ImageSupport\Filesystem;

    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Xaduken\ImageSupport\DTO\ImageInfo;

    interface FilesystemUploaderInterface
    {
        public function upload(UploadedFile $uploadedFile, string $targetDirectory): ImageInfo;
    }

method upload should save image to filesystem.
- $uploadedFile - file from symfony request
- $targetDirectory - directory where file will be save

Method should return FileInfo DTO object, which will be used for 
storing into database.

### And maybe you find needle realization of factory in this list:
- SimpleImageManagerFactory - just save image to database and filesystem.

not so much right now ;)


