import pyautogui
import time
import keyboard

def auto_click(interval=0.00000000001):  # Decreased interval to 0.1 seconds
    while True:
        if keyboard.is_pressed('`'):  # Check if "`" key is pressed
            print("Auto-click started. Press 'Esc' to stop.")
            try:
                while True:
                    x, y = pyautogui.position()  # Get current mouse position
                    pyautogui.click(x, y)  # Perform click at current position
                    if keyboard.is_pressed('esc'):  # Check if Escape key is pressed
                        print("\nAuto-click stopped.")
                        break
                    time.sleep(interval)  # Wait for the specified interval before the next click
            except KeyboardInterrupt:
                print("\nAuto-click stopped.")
        time.sleep(0.1)  # Pause to avoid high CPU usage

if __name__ == "__main__":
    auto_click()
