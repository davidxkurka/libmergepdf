<?php

declare(strict_types = 1);

namespace iio\libmergepdf;

use iio\libmergepdf\Driver\DriverInterface;
use iio\libmergepdf\Driver\DefaultDriver;
use iio\libmergepdf\Source\SourceInterface;
use iio\libmergepdf\Source\FileSource;
use iio\libmergepdf\Source\RawSource;

/**
 * Merge existing pdfs into one
 *
 * Note that your PDFs are merged in the order that you add them
 */
final class Merger
{
    /**
     * @var SourceInterface[] List of pdf sources to merge
     */
    private $sources = [];

    /**
     * @var DriverInterface
     */
    private $driver;

    public function __construct(DriverInterface|null $driver = null)
    {
        $this->driver = $driver ?: new DefaultDriver;
    }

    /**
     * Add raw PDF from string
     */
    public function addRaw(string $content, PagesInterface|null $pages = null): void
    {
        $this->sources[] = new RawSource($content, $pages);
    }

    /**
     * Add PDF from file
     */
    public function addFile(string $filename, PagesInterface|null $pages = null): void
    {
        $this->sources[] = new FileSource($filename, $pages);
    }

    /**
     * Add files using iterator
     *
     * @param iterable<string> $iterator Set of filenames to add
     * @param PagesInterface|null $pages Optional pages constraint used for every added pdf
     */
    public function addIterator(iterable $iterator, PagesInterface|null $pages = null): void
    {
        foreach ($iterator as $filename) {
            $this->addFile($filename, $pages);
        }
    }

    /**
     * Merges loaded PDFs
     */
    public function merge(): string
    {
        return $this->driver->merge(...$this->sources);
    }

    /**
     * Reset internal state
     */
    public function reset(): void
    {
        $this->sources = [];
    }
}
