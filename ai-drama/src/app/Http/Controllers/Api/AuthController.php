<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Services\Auth\AuthService;
use App\Services\Auth\SmsService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private AuthService $authService,
        private SmsService $smsService
    ) {}

    /**
     * 发送短信验证码
     */
    public function sendSms(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|regex:/^1[3-9]\d{9}$/',
            'scene' => ['required', Rule::in(['login', 'register', 'reset'])],
        ]);

        $result = $this->smsService->send($data['phone'], $data['scene']);

        return $this->success($result, '验证码已发送');
    }

    /**
     * 注册（手机号 + 验证码）
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|regex:/^1[3-9]\d{9}$/',
            'code' => 'required|digits:6',
            'invite_code' => 'nullable|string|size:8',
        ]);

        $user = $this->authService->registerByPhone(
            $data['phone'],
            $data['code'],
            $data['invite_code'] ?? null
        );

        $token = $user->createToken('aidrama')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => $this->formatUser($user),
        ], '注册成功', 201);
    }

    /**
     * 登录（手机号 + 验证码）
     */
    public function loginByPhone(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|regex:/^1[3-9]\d{9}$/',
            'code' => 'required|digits:6',
        ]);

        $user = $this->authService->loginByPhone($data['phone'], $data['code']);
        $token = $user->createToken('aidrama')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => $this->formatUser($user),
        ], '登录成功');
    }

    /**
     * 登录（邮箱 + 密码）
     */
    public function loginByEmail(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = $this->authService->loginByEmail($data['email'], $data['password']);
        $token = $user->createToken('aidrama')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => $this->formatUser($user),
        ], '登录成功');
    }

    /**
     * 登出
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, '已登出');
    }

    /**
     * 当前用户信息
     */
    public function me(Request $request)
    {
        return $this->success($this->formatUser($request->user()));
    }

    private function formatUser($user): array
    {
        $user->load('roles', 'permissions');
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'phone' => $user->phone_masked,
            'email' => $user->email,
            'member_level' => $user->member_level,
            'member_expires_at' => $user->member_expires_at?->toIso8601String(),
            'real_name_status' => $user->real_name_status,
            'invite_code' => $user->invite_code,
            'roles' => $user->roles->pluck('name'),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'created_at' => $user->created_at->toIso8601String(),
        ];
    }
}
