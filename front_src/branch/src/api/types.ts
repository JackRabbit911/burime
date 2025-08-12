export type ApiResponse<T = any, E = any> = {
  success: boolean;
  error?: E;
  result: T;
};
