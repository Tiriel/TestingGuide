<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Zaslavsky
 * Date: 27/11/17
 * Time: 14:24
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Users
        $firstnames = [
            'Foo',
            'Bar',
            'Waldo',
        ];
        $lastnames = [
            'Baz',
            'Quux',
            'Thud',
        ];
        $usernames = [
            'corge',
            'xyzzy',
            'plugh',
        ];
        $mails = [
            'foobaz@cron.com',
            'bazquux@fran.com',
            'waldothud@plugh.com',
        ];

        //Posts
        $titles = [
            'How long before you can make the jump to light speed?',
            'Sir, if you\'ll not be needing me, I\'ll close down for awhile.',
            'Obi-Wan Kenobi...Obi-Wan? Now thats a name I haven\'t heard in a long time...',
        ];

        $contents = [
            'It\'ll take a few moments to get the coordinates from the navi-computer. Are you kidding? At the rate they\'re gaining... Traveling through hyperspace isn\'t like dusting crops, boy! Without precise calculations we could fly right through a star or bounce too close to a supernova and that\'d end your trip real quick, wouldn\'t it? What\'s that flashing? We\'re losing our deflector shield. Go strap yourself in, I\'m going to make the jump to light speed.',
            'Sure, go ahead. What is it? Your fathers lightsaber. This is the weapon of a Jedi Knight. Not as clumsy or as random as a blaster. An elegant weapon for a more civilized time. For over a thousand generations the Jedi Knights were the guardians of peace and justice in the Old Republic. Before the dark times, before the Empire. How did my father die? A young Jedi named Darth Vader, who was a pupil of mine until he turned to evil, helped the Empire hunt down and destroy the Jedi Knights. He betrayed and murdered your father. Now the Jedi are all but extinct. Vader was seduced by the dark side of the Force. The Force?',
            'I think my uncle knew him. He said he was dead. Oh, he\'s not dead, not...not yet. You know him! Well of course, of course I know him. He\'s me! I haven\'t gone by the name Obi-Wan since oh, before you were born. Then the droid does belong to you. Don\'t seem to remember ever owning a droid. Very interesting... I think we better get indoors. The Sandpeople are easily startled but they will soon be back and in greater numbers. Threepio! Where am I? I must have taken a bad step... Can you stand? We\'ve got to get out of here before the Sandpeople return. I don\'t think I can make it. You go on, Master Luke. There\'s no sense in you risking yourself on my account. I\'m done for. No, you\'re not. What kind of talk is that? Quickly, son...they\'re on the move.',
        ];

        //Building fixtures
        $encoder = $this->container->get('security.password_encoder');
        for ($i = 0; $i < count($usernames); $i++) {
            $interval = $i == 1 ? $i.' day': $i.' days';
            $createdAt = new \DateTime();
            $createdAt
                ->setTimestamp(1510754739)
                ->add(\DateInterval::createFromDateString($interval));
            $postedAt = new \DateTime();
            $postedAt
                ->setTimestamp(1511186821)
                ->add(\DateInterval::createFromDateString($interval));

            $user = new User();
            $user->setFirstname($firstnames[$i])
                ->setLastname($lastnames[$i])
                ->setUsername($usernames[$i])
                ->setEmail($mails[$i])
                ->setCreatedAt($createdAt)
                ->setPassword(
                    $encoder->encodePassword($user, $usernames[$i])
                );
            $manager->persist($user);

            $post = new Post();
            $post->setTitle($titles[$i])
                ->setContent($contents[$i])
                ->setAuthor($user)
                ->setPostedAt($postedAt)
                ->setPrivate(false);
            $manager->persist($post);
        }
        $manager->flush();
    }
}