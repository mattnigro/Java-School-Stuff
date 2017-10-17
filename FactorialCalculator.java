/*
This shows recursion in action
*/
package factorialcalculator;

/**
 *
 * @author me
 */
public class FactorialCalculator
{
    public static int spacing;
   // recursive method factorial (assumes its parameter is >= 0
    public static long factorial(long number)
    {
        int spacing = FactorialCalculator.spacing;
        spacing++;
        for(int i = 0; i < spacing; i++){
            System.out.print(' ');
        }
        FactorialCalculator.spacing = spacing;
        System.out.println("Recursive Call: " + number + " * fact(" + (number-1) + ")");
        if (number <= 1) // test for base case
            return 1; // base cases: 0! = 1 and 1! = 1
        else{// recursion step
           return number * factorial(number - 1);
        }
    } 

    // output factorials for values 0-21
    public static void main(String[] args)
    {
        
        System.out.println("Matthew Nigro - Assignment 6A");
       // calculate the factorials of 0 through 21
       for (int counter = 0; counter <= 21; counter++){
           
           FactorialCalculator.spacing = 0;
          System.out.printf("%d! = %d%n", counter, factorial(counter));
       }
    }
} // end class FactorialCalculator