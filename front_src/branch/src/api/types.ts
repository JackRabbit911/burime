export type ApiResponse<T, E = any> = {
  success: boolean;
  error?: E;
  result: T;
};
