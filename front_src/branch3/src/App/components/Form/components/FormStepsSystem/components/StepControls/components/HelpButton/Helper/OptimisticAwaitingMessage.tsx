const OptimisticAwaitingMessage = () => (
  <div className="flex flex-col justify-center min-h-64">
    <div className="text-center w-full">
      <span className="text-xl">
        Ждите, помощь идёт
      </span>
      <span className="loading loading-dots loading-xs ms-1.5 mt-2.5"></span>
    </div>
  </div>
);

export default OptimisticAwaitingMessage;
