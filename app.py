from pytube import YouTube

app = Flask(__name__)

def download_youtube_video(url, save_path):
    try:
        yt = YouTube(url)
        video = yt.streams.filter(progressive=True, file_extension='mp4').first()
        video.download(save_path)
        return "Video downloaded successfully!"
    except Exception as e:
        return f"Error downloading video: {e}"

@app.route('/')
def index():
    return render_template('downloadmp4.html')

@app.route('/download', methods=['POST'])
def download():
    video_url = request.form['video_url']
    save_path = request.form['save_path']
    message = download_youtube_video(video_url, save_path)
    return message

if __name__ == '__main__':
    app.run(debug=True)
