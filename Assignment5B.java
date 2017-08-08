// Matthew Nigro - CSIS 220
// Assignment 5B - Roll 2d6 dice 36,000
package assignment5b;
import java.security.SecureRandom;

public class Assignment5B {

    public static void main(String[] args) {
                      
        System.out.println("Matthew Nigro - Assignment 5B\n");
        SecureRandom $die = new SecureRandom();
        int $count = 0;
        int[] $_roll = new int[13];
        
        while($count < 36000){
            $count++;        
            int $d1 = 1 + $die.nextInt(6);        
            int $d2 = 1 + $die.nextInt(6);
            int $sum = $d1 + $d2;
            $_roll[$sum]++;
            //System.out.println($sum);
        }
        //System.out.println($_roll.length);
        int $key = 2;
        System.out.printf("%s%8s%n", "ROLL","TIMES");
        while ($key <= 12){
            
            int $sum = $_roll[$key];
            System.out.printf("%3s%8s%n", $key, $sum);
            $key++;
        }
    }
}
