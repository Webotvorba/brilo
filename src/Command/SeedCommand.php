<?php

namespace App\Command;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(name: 'app:seed-data')]
class SeedCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly HttpClientInterface $httpClient,
        private readonly ValidatorInterface $validator,
        private readonly PropertyAccessorInterface $propertyAccessor,
    ) {
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $endpoints = [
            'users' => "https://jsonplaceholder.typicode.com/users",
            'posts' => "https://jsonplaceholder.typicode.com/posts",
            'comments' => "https://jsonplaceholder.typicode.com/comments"
        ];
        $responseData = [];

        $output->writeln('<info>Getting data...</info>');
        foreach ($endpoints as $entity => $endpoint)
        {
            $response = $this->httpClient->request('GET', $endpoint);
            if ($response->getStatusCode() !== 200)
            {
                $output->writeln('<error>There was an error getting data, HTTP/' . $response->getStatusCode() . '</error>');
                return Command::FAILURE;
            }

            $responseData[$entity] = $response->toArray();
        }

        $output->writeln('<info>Seeding data (Users)...</info>');
        foreach($responseData['users'] as $key => $userData)
        {
            $user = new User();
            $user->setName($this->propertyAccessor->getValue($userData, '[name]'));
            $user->setUsername($this->propertyAccessor->getValue($userData, '[username]'));
            $user->setEmail($this->propertyAccessor->getValue($userData, '[email]'));
            $user->setAddress($this->propertyAccessor->getValue($userData, '[address]'));
            $user->setPhone($this->propertyAccessor->getValue($userData, '[phone]'));
            $user->setCompany($this->propertyAccessor->getValue($userData, '[company]'));
            $user->setWebsite($this->propertyAccessor->getValue($userData, '[website]'));

            $errors = $this->validator->validate($user);
            if (count($errors) > 0)
            {
                $output->writeln("<error>User data failed validation, loop index: $key</error>");
            }   else
            {
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }
        }
        $output->writeln('<info>Seeding data (Users) infoful...</info>');

        $output->writeln('<info>Seeding data (Posts)...</info>');
        foreach($responseData['posts'] as $key => $postData)
        {
            $post = new Post();
            $post->setTitle($this->propertyAccessor->getValue($postData, '[title]'));
            $post->setBody($this->propertyAccessor->getValue($postData, '[body]'));

            $userID = $this->propertyAccessor->getValue($postData, '[userId]');
            $user = $this->entityManager->getReference(User::class, $userID);
            $post->setAuthor($user);

            $errors = $this->validator->validate($post);
            if (count($errors) > 0)
            {
                $output->writeln("<error>Post data failed validation, loop index: $key</error>");
            }   else
            {
                $this->entityManager->persist($post);
                $this->entityManager->flush();
            }
        }
        $output->writeln('<info>Seeding data (Posts) infoful...</info>');

        $output->writeln('<info>Seeding data (Comments)...</info>');
        foreach($responseData['comments'] as $key => $commentData)
        {
            $comment = new Comment();
            $comment->setName($this->propertyAccessor->getValue($commentData, '[name]'));
            $comment->setEmail($this->propertyAccessor->getValue($commentData, '[email]'));
            $comment->setBody($this->propertyAccessor->getValue($commentData, '[body]'));

            $postID = $this->propertyAccessor->getValue($commentData, '[postId]');
            $post = $this->entityManager->getReference(Post::class, $postID);
            $comment->setPost($post);

            $errors = $this->validator->validate($comment);
            if (count($errors) > 0)
            {
                $output->writeln("<error>Comment data failed validation, loop index: $key</error>");
            }   else
            {
                $this->entityManager->persist($comment);
                $this->entityManager->flush();
            }
        }
        $output->writeln('<info>Seeding data (Comments) infoful...</info>');
        return Command::SUCCESS;
    }
}