import random

def guessing_game():
    print("Welcome to the Guessing Game!")
    print("I have selected a number between 1 and 50.")
    print("You have 7 attempts to guess it correctly.")

    # Generate a random number between 1 and 100
    secret_number = random.randint(1, 50)
    attempts = 0

    while attempts < 7:
        try:
            guess = int(input("Enter your guess: "))
            attempts += 1

            if guess < secret_number:
                print("Too low! Try again.")
            elif guess > secret_number:
                print("Too high! Try again.")
            else:
                print(f"Congratulations! You've guessed the number ({secret_number}) correctly in {attempts} attempts!")
                return

        except ValueError:
            print("Invalid input! Please enter a valid number.")

    print(f"Sorry, you've run out of attempts. The correct number was {secret_number}.")

# Run the game
guessing_game()
