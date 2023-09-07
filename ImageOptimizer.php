<?php

class ImageOptimizer
{
    const MAX_WIDTH = 2560;
    const MAX_HEIGHT = 2560;
    private array|false $imageInfo;
    private string $path;
    private string $outputDirectory;
    private string $fileName;
    private array $supportedMimes = [
        'image/jpeg',
        'image/png',
        'image/avif',
        'image/bmp',
        'image/tiff'
    ];

    public function __construct(string $directory, string $fileName, array $imageInfo, string $outputDirectory)
    {
        $this->path = $directory . $fileName;
        $this->outputDirectory = $outputDirectory;
        $this->imageInfo = $imageInfo;
        $this->fileName = $fileName;
    }


    public function convertToWebp(GdImage $image): void
    {
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        imagewebp($image, $this->outputDirectory . self::replaceExtension($this->fileName, "webp"));
    }

    private function replaceExtension(string $filename, string $newExtension): string
    {
        $info = pathinfo($filename);

        return $info['filename'] . '.' . $newExtension;
    }


    public function resizeImage(GdImage $img)
    {

        if (self::getAspectRatio() >= 1 && self::getInputWidth() > self::MAX_WIDTH) {
            return imagescale($img, self::MAX_WIDTH, round(self::MAX_WIDTH / self::getAspectRatio()));
        }


        if (self::getAspectRatio() < 1 && self::getInputHeight() > self::MAX_HEIGHT) {
            return imagescale($img, round(self::MAX_HEIGHT * self::getAspectRatio()), self::MAX_HEIGHT);
        }

        return $img;
    }

    private function getInputWidth(): int
    {
        return $this->imageInfo[0];
    }

    private function getInputHeight(): int
    {
        return $this->imageInfo[1];
    }

    private function getAspectRatio(): float
    {
        return self::getInputWidth() / self::getInputHeight();
    }

    public function initializeImage(): GdImage|false
    {
        if (!self::isSupportedType()) {
            return false;
        }

        return self::getImageIdentifier();
    }


    private function isSupportedType(): bool
    {
        return in_array(self::getMime(), $this->supportedMimes);
    }

    public function getMime(): string
    {
        return $this->imageInfo["mime"];
    }

    private function getImageIdentifier(): GdImage|false
    {
        if (self::getMime() === 'image/jpeg') {
            return imagecreatefromjpeg($this->path);
        }

        if (self::getMime() === 'image/png') {
            return imagecreatefrompng($this->path);
        }

        if (self::getMime() === 'image/avif') {
            return imagecreatefromavif($this->path);
        }

        if (self::getMime() === 'image/bmp') {
            return imagecreatefrombmp($this->path);
        }

        if (self::getMime() === 'image/tiff') {
            return imagecreatefrombmp($this->path);
        }
        return false;
    }

}