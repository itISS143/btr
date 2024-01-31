#include <SFML/Graphics.hpp>

int main() {
    // Membuat window dengan ukuran 800x600 pixel
    sf::RenderWindow window(sf::VideoMode(800, 600), "Simple Game");

    // Membuat lingkaran sebagai objek pemain
    sf::CircleShape player(50);
    player.setFillColor(sf::Color::Green);
    player.setPosition(400, 300);

    // Loop utama permainan
    while (window.isOpen()) {
        // Event handling
        sf::Event event;
        while (window.pollEvent(event)) {
            if (event.type == sf::Event::Closed)
                window.close();
        }

        // Gerakan pemain
        if (sf::Keyboard::isKeyPressed(sf::Keyboard::Left))
            player.move(-0.1, 0);
        if (sf::Keyboard::isKeyPressed(sf::Keyboard::Right))
            player.move(0.1, 0);
        if (sf::Keyboard::isKeyPressed(sf::Keyboard::Up))
            player.move(0, -0.1);
        if (sf::Keyboard::isKeyPressed(sf::Keyboard::Down))
            player.move(0, 0.1);

        // Clear window
        window.clear();

        // Gambar objek pemain
        window.draw(player);

        // Menampilkan window
        window.display();
    }

    return 0;
}
