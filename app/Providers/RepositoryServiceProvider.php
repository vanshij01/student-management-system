<?php

namespace App\Providers;

use App\Contract\BedRepositoryInterface;
use App\Contract\CountryRepositoryInterface;
use App\Contract\CourseRepositoryInterface;
use App\Contract\DocumentTypeRepositoryInterface;
use App\Contract\EventsRepositoryInterface;
use App\Contract\HostelRepositoryInterface;
use App\Contract\RoomRepositoryInterface;
use App\Contract\StudentDocumentRepositoryInterface;
use App\Contract\StudentRepositoryInterface;
use App\Contract\UserRepositoryInterface;
use App\Contract\VillageRepositoryInterface;
use App\Contract\WardenRepositoryInterface;
use App\Repositories\BedRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CourseRepository;
use App\Repositories\DocumentTypeRepository;
use App\Repositories\EventsRepository;
use App\Repositories\HostelRepository;
use App\Repositories\RoomRepository;
use App\Repositories\StudentDocumentRepository;
use App\Repositories\StudentRepository;
use App\Repositories\UserRepository;
use App\Repositories\VillageRepository;
use App\Repositories\WardenRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BedRepositoryInterface::class, BedRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(DocumentTypeRepositoryInterface::class, DocumentTypeRepository::class);
        $this->app->bind(HostelRepositoryInterface::class, HostelRepository::class);
        $this->app->bind(RoomRepositoryInterface::class, RoomRepository::class);
        $this->app->bind(StudentDocumentRepositoryInterface::class, StudentDocumentRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(VillageRepositoryInterface::class, VillageRepository::class);
        $this->app->bind(WardenRepositoryInterface::class, WardenRepository::class);
        $this->app->bind(EventsRepositoryInterface::class, EventsRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
