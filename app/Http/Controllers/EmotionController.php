<?php

namespace App\Http\Controllers;

use App\Enums\EmotionType;
use App\Models\Emotion;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmotionController extends Controller
{
    public function toggleEmotion(Request $request, Post $post)
    {
        
        $user = Auth::user();

        $request->validate([
            'type' => ['required', 'string', new \Illuminate\Validation\Rules\Enum(EmotionType::class)],
        ]);

        $emotionType = EmotionType::from($request->type); // Chuyển đổi chuỗi thành Enum

        //emotiontion hiện có của người dùng cho bài viết này
        $existingEmotion = Emotion::where('user_id', $user->id)
                                        ->where('post_id', $post->id)
                                        ->first();

        // Logic xử lý:
        if ($existingEmotion) {
            // Nếu đã có emotion
            if ($existingEmotion->type === $emotionType) {
                // Nếu emotion hiện tại giống với emotion mới gửi lên, loại "bỏ" cảm xúc
                $existingEmotion->delete();
                return response()->json([
                    'message' => 'Đã gỡ cảm xúc thành công.',
                    'status' => 'removed'
                ]);
            } else {
                // Nếu emotion hiện tại khác emotion mới, cập nhật lại
                $existingEmotion->type = $emotionType;
                $existingEmotion->save();
                return response()->json([
                    'message' => 'Đã cập nhật cảm xúc thành công.',
                    'status' => 'updated',
                    'new_type' => $emotionType->value
                ]);
            }
        } else {
            // Nếu chưa có emotion nào, tạo mới
            Emotion::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'type' => $emotionType,
            ]);
            return response()->json([
                'message' => 'Đã thêm cảm xúc thành công.',
                'status' => 'added',
                'type' => $emotionType->value
            ], 201); 
        }
    }



    /**
     * Lấy tổng số like và dislike cho một bài viết.
     */
    public function getEmotionCounts(Post $post)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userEmotion = Emotion::where('user_id', $user->id)->where('post_id', $post->id)->first();
        
        } else {
            $userEmotion = 'none';
        }

        $likes = $post->emotions()->where('type', EmotionType::Like)->count();
        $dislikes = $post->emotions()->where('type', EmotionType::Dislike)->count();

        return response()->json([
            'likes' => $likes,
            'dislikes' => $dislikes,
            'userEmotion' => $userEmotion
        ]);
    }

}
