from pytube import YouTube
import os

def download_youtube_video_as_mp3(url, save_path):
    try:
        yt = YouTube(url)
        audio_stream = yt.streams.filter(only_audio=True).first()
        audio_stream.download(output_path=save_path)
        original_filename = audio_stream.default_filename
        new_filename = os.path.splitext(original_filename)[0] + '.mp3'
        os.rename(os.path.join(save_path, original_filename), os.path.join(save_path, new_filename))
        print("Video downloaded successfully as MP3!")
    except Exception as e:
        print(f"Error downloading video: {e}")

if __name__ == "__main__":
    # Example usage:
    video_url = input("Enter the YouTube video URL: ")
    save_path = input("Enter the path to save the video (or leave blank for current directory): ").strip() or '.'
    download_youtube_video_as_mp3(video_url, save_path)
