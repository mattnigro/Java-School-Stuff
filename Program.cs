using System;

namespace HelloWorld
{
    class Program
    {
        static void Main(string[] args)
        {
            var password = "";
            var count = 0;
            var name = "";
            while (name == "")
            {
                Console.WriteLine("What is your name?");
                name = Console.ReadLine();
            }
            Console.WriteLine("Thanks, {0}", name);
            while (password != "password")
            {
                count++;
                if (count > 3)
                {
                    Console.WriteLine("No dice!");
                    break;
                }
                Console.WriteLine("Authenticate your password:");
                password = Console.ReadLine();
            }
            if (count > 3)
            {
                Console.WriteLine("Too many failed attempts");
                Console.Beep(7734, 1000);
                Console.Beep(3457, 1000);
            }
            else
            {
                Console.WriteLine("AUTHENTICATED: Hello {0}!", name);
                Console.Beep(3747, 1000);
                Console.Beep(7734, 1000);
            }
        }
    }
}
