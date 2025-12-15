export type ApiResponse<T, E = string> = {
  success: boolean;
  error?: E;
  result: T;
};
