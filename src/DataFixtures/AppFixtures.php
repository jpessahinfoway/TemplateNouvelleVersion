<?php

namespace App\DataFixtures;


use App\Entity\Main\Customer;
use App\Entity\Main\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface encoder for user password
     */
    private $encoder;


    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        //===============    creation start     ===============//


        // create customer start
        $customerKFC = new Customer();
        $customerKFC->setName("KFC");
        // create customer end


        // create users start
        $userDemo = new User();
        $userDemo->setUsername("demo")
            // encode password encodePassword($object, $string)
            // $object must implement Symfony\Component\Security\Core\User\UserInterface
                 ->setUserPassword($this->encoder->encodePassword($userDemo, "demo"));


        $userRoot = new User();
        $userRoot->setUsername("root")
                 ->setUserPassword($this->encoder->encodePassword($userDemo, "root"));
        // create users end


        //===============    creation end     ===============//



        //===============    adding start     ===============//


        // adding roles to user start
        $userDemo->setRole("site_kfc");
        $userRoot->setRole("site_kfc");
        // adding roles to user end


        // adding user to customer start
        $customerKFC->addUser($userDemo);
        $customerKFC->addUser($userRoot);
        // adding user to customer end


        // adding customer to user start
        $userDemo->setCustomer($customerKFC);

        $userRoot->setCustomer($customerKFC);
        // adding customer to user end




        //===============    adding end     ===============//



        //===============    entity persisting start     ===============//



        // persisting users start
        $manager->persist($userDemo);
        $manager->persist($userRoot);
        // persisting users end


        // persisting customer start
        $manager->persist($customerKFC);
        // persisting customer end

        //===============    entity persisting end     ===============//




        //===============    entity flush start     ===============//

        $manager->flush();

        //===============    entity flush end     ===============//


    }
}
