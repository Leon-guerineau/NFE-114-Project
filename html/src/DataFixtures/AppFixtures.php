<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Genre;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Game;
use Bezhanov\Faker\ProviderCollectionHelper;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures implements FixtureInterface
{
    const NB_OF_USERS_CREATED = 100; // +3 manually created users
    private array $dummyUsers = [];

    const NB_OF_GENRES_CREATED = 50; // +2 manually created genres
    private array $dummyGenres = [];

    const NB_OF_GAMES_CREATED = 100; // +2 manually created games
    private array $dummyGames = [];

    const NB_OF_POSTS_CREATED = 500; // +2 manually created posts
    private array $dummyPosts = [];

    const NB_OF_COMMENTS_CREATED = 750; // +2 manually created comments

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        ProviderCollectionHelper::addAllProvidersTo($faker);

        // Users

        $jean = (new User())
            ->setEmail('jean@gmail.com')
            ->setPassword('password')
            ->setUsername("jean1234")
            ->setUpdatedAt(null)
        ;
        $manager->persist($jean);

        $alex = (new User())
            ->setEmail('alex@gmail.com')
            ->setPassword('password')
            ->setUsername('00alex00')
            ->setUpdatedAt(null)
        ;
        $manager->persist($alex);

        $sacha = (new User())
            ->setEmail('sacha@gmail.com')
            ->setPassword('password')
            ->setUsername('sachazerty')
            ->setUpdatedAt(null)
        ;
        $manager->persist($sacha);

        // Dummy Users
        for ($i = 0; $i < self::NB_OF_USERS_CREATED; $i++) {
            $fakeUsername = $faker->userName();
            $dummyUser = (new User())
                ->setUsername($fakeUsername)
                ->setEmail("$fakeUsername@gmail.com")
                ->setPassword('password')
                ->setUpdatedAt(null)
            ;
            $manager->persist($dummyUser);
            $this->dummyUsers[$i] = $dummyUser;
        }

        // Genres

        $mmorpg = (new Genre())
            ->setLabel('mmorpg')
        ;
        $manager->persist($mmorpg);

        $sandbox = (new Genre())
            ->setLabel('sandbox')
        ;
        $manager->persist($sandbox);

        // Dummy Genres

        for ($i = 0; $i < self::NB_OF_GENRES_CREATED; $i++) {
            $dummy_genre = (new Genre())
                ->SetLabel($faker->word())
            ;
            $manager->persist($dummy_genre);
            $this->dummyGenres[$i] = $dummy_genre;
        }

        //Games

        $ff14 = (new Game())
            ->setTitle('ff14')
            ->setReleaseDate(date_create_immutable())
            ->addGenre($mmorpg)
        ;
        $manager->persist($ff14);

        $minecraft = (new Game())
            ->setTitle('minecraft')
            ->setReleaseDate(date_create_immutable())
            ->addGenre($sandbox)
        ;
        $manager->persist($minecraft);

        //Dummy Games

        for ($i = 0; $i < self::NB_OF_GAMES_CREATED; $i++) {
            $randomGenre = $this->dummyGenres[array_rand($this->dummyGenres)];
            $dummyGame = (new Game())
                ->setTitle('G_' . $faker->word())
                ->setReleaseDate($faker->dateTime)
                ->addGenre($randomGenre)
            ;
            $manager->persist($dummyGame);
            $this->dummyGames[$i] = $dummyGame;
        }

        //Posts

        $firstPost = (new Post())
            ->setUser($jean)
            ->setGame($minecraft)
            ->setTitle('Très beau jeu')
            ->setDescription('')
            ->setUpdatedAt(null)
        ;
        $manager->persist($firstPost);

        $secondPost = (new Post())
            ->setUser($alex)
            ->setGame($ff14)
            ->setTitle('Gameplay incroyable')
            ->setDescription('desc')
            ->setUpdatedAt(null)
        ;
        $manager->persist($secondPost);

        //Dummy Posts

        for ($i = 0; $i < self::NB_OF_POSTS_CREATED; $i++) {
            $randomUser = $this->dummyUsers[array_rand($this->dummyUsers)];
            $randomGame = $this->dummyGames[array_rand($this->dummyGames)];
            $dummyPost = (new post())
                ->setUser($randomUser)
                ->setGame($randomGame)
                ->setTitle('P_' . $faker->word())
                ->setDescription($faker->sentence(10))
                ->setUpdatedAt(null)
            ;
            $manager->persist($dummyPost);
            $this->dummyPosts[$i] = $dummyPost;
        }

        //Comments

        $firstComment = (new Comment())
            ->setUser($jean)
            ->setPost($secondPost)
            ->setContent('yes')
            ->setUpdatedAt(null)
        ;
        $manager->persist($firstComment);

        $secondComment = (new Comment())
            ->setUser($alex)
            ->setPost($firstPost)
            ->setContent('no')
            ->setUpdatedAt(null)
        ;
        $manager->persist($secondComment);

        //Dummy Comments

        for ($i = 0; $i < self::NB_OF_COMMENTS_CREATED; $i++) {
            $randomUser = $this->dummyUsers[array_rand($this->dummyUsers)];
            $randomGame = $this->dummyPosts[array_rand($this->dummyPosts)];
            $dummyComment = (new Comment())
                ->setUser($randomUser)
                ->setPost($randomGame)
                ->setContent($faker->sentence(10))
                ->setUpdatedAt(null)
            ;
            $manager->persist($dummyComment);
        }

        //Flush

        $manager->flush();
    }
}
