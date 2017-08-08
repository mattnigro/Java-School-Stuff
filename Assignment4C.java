// Matthew Nigro - CSIS 220
// Assignment 4C - Help elementary students learn basic multiplication
package assignment4c;
import java.security.SecureRandom;
import java.util.Scanner;

public class Assignment4C {

    public static void main(String[] args) {
              
        System.out.println("Matthew Nigro - Assignment 4B\n");
        SecureRandom $randNum = new SecureRandom();
        int $rand1 = 1 + $randNum.nextInt(9); //lazy students
        int $rand2 = 1 + $randNum.nextInt(9); //homework cost
        int $extortionAmount = ($rand1 * $rand2); // correct potential amount extorted
        int $guessAnswer;
        /* USE CORRECT GRAMMAR FOR NUMBER OF STUDENTS */
        String $lazyClassmates = " classmates each";
        String $students = " those students' assignments?:\nAttempt ";
        String $extortedSutdents = " from those students!";
        int $count = 0;
        do {
            $count++;
            if ($rand1 == 1){
                $lazyClassmates = " classmate";
                $students = " that student's assignment?:\nAttempt ";
                $extortedSutdents = " from that student!";
            }        
            System.out.print("If " + $rand1 + $lazyClassmates + " paid you $" 
                + $rand2 + " to do their homework assignment, \n"
                + "how much would you make if you did" + $students + $count + ": $");
            Scanner $input = new Scanner(System.in);
            $guessAnswer = $input.nextInt();
            $guessAnswer = $checkExtortion($guessAnswer, $extortionAmount);
        }
        while($guessAnswer != $extortionAmount);        
        System.out.println("Good job! You would have extorted $" + $extortionAmount + $extortedSutdents);      
    }
    public static int $checkExtortion(int $guessAnswer, int $extortionAmount){

        if ($guessAnswer == $extortionAmount){
            return $guessAnswer;
        }
        else {
            System.out.print("Sorry. $" + $guessAnswer + " is not correct. Please try again."
                    + "\n--------------------------------------------\n");
            
        }
        return $guessAnswer;
    }
    
}
